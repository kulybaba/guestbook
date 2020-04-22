<?php

namespace App\MessageHandler;

use App\Entity\Comment;
use App\Message\CommentMessage;
use App\Notification\CommentReviewNotification;
use App\Repository\CommentRepository;
use App\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class CommentMessageHandler implements MessageHandlerInterface
{
    /**
     * @var SpamChecker $spamChecker
     */
    private $spamChecker;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var CommentRepository $commentRepository
     */
    private $commentRepository;

    /**
     * @var MessageBusInterface $messageBus
     */
    private $messageBus;

    /**
     * @var WorkflowInterface $workflow
     */
    private $workflow;

    /**
     * @var NotifierInterface $notifier
     */
    private $notifier;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * CommentMessageHandler constructor.
     * @param SpamChecker $spamChecker
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     * @param MessageBusInterface $messageBus
     * @param WorkflowInterface $commentStateMachine
     * @param NotifierInterface $notifier
     * @param LoggerInterface|null $logger
     * @param string $adminEmail
     */
    public function __construct(
        SpamChecker $spamChecker,
        EntityManagerInterface $entityManager,
        CommentRepository $commentRepository,
        MessageBusInterface $messageBus,
        WorkflowInterface $commentStateMachine,
        NotifierInterface $notifier,
        LoggerInterface $logger = null,
        string $adminEmail
    ) {
        $this->spamChecker = $spamChecker;
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->messageBus = $messageBus;
        $this->workflow = $commentStateMachine;
        $this->notifier = $notifier;
        $this->logger = $logger;
    }

    public function __invoke(CommentMessage $commentMessage)
    {
        $comment = $this->commentRepository->find($commentMessage->getId());
        if (!$comment) {
            return;
        }

        if ($this->workflow->can($comment, Comment::STATE_ACCEPT)) {
            $score = $this->spamChecker->getSpamScore($comment, $commentMessage->getContext());
            $transition = Comment::STATE_ACCEPT;
            if ($score === 2) {
                $transition = Comment::STATE_REJECT_SPAM;
            } elseif ($score === 1) {
                $transition = Comment::STATE_MIGHT_BE_SPAM;
            }
            $this->workflow->apply($comment, $transition);

            $this->entityManager->flush();
            $this->messageBus->dispatch($commentMessage);
        } elseif ($this->workflow->can($comment, Comment::STATE_PUBLISHED) || $this->workflow->can($comment, Comment::STATE_PUBLISH_HAM)) {
            $this->notifier->send(new CommentReviewNotification($comment, $commentMessage->getReviewUrl()), $this->notifier->getAdminRecipients());
        } elseif ($this->logger) {
            $this->logger->debug('Dropping comment message', [
                'comment' => $comment->getId(),
                'state' => $comment->getState()
            ]);
        }
    }
}
