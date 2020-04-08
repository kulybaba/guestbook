<?php

namespace App\MessageHandler;

use App\Entity\Comment;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

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
     * @param SpamChecker $spamChecker
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     */
    public function __construct(SpamChecker $spamChecker, EntityManagerInterface $entityManager, CommentRepository $commentRepository)
    {
        $this->spamChecker = $spamChecker;
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(CommentMessage $commentMessage)
    {
        $comment = $this->commentRepository->find($commentMessage->getId());
        if (!$comment) {
            return;
        }

        if ($this->spamChecker->getSpamScore($comment, $commentMessage->getContext()) === 2) {
            $comment->setState(Comment::STATE_SPAM);
        } else {
            $comment->setState(Comment::STATE_PUBLISHED);
        }

        $this->entityManager->flush();
    }
}
