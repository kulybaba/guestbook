<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Message\CommentMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/comment/review/{id}", name="admin_comment_review")
     */
    public function commentReview(
        Request $request,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        Registry $registry,
        Comment $comment
    ) {
        $accepted = $request->query->get('reject', false);

        $machine = $registry->get($comment);
        if ($machine->can($comment, Comment::STATE_PUBLISHED)) {
            $transition = $accepted ? Comment::STATE_PUBLISHED : Comment::STATE_REJECT;
        } elseif ($machine->can($comment, Comment::STATE_PUBLISH_HAM)) {
            $transition = $accepted ? Comment::STATE_PUBLISH_HAM : Comment::STATE_REJECT_SPAM;
        } else {
            return new Response('Comment already reviewed or not in the rightstate.');
        }

        $machine->apply($comment, $transition);
        $entityManager->flush();

        if ($accepted) {
            $messageBus->dispatch(new CommentMessage($comment->getId()));
        }

        return $this->render('admin/comment_review.html.twig', [
            'comment' => $comment,
            'transition' => $transition,
        ]);
    }

    /**
     * @Route("/http-cache/{uri<.*>}", methods={"PURGE"})
     */
    public function purgeHttpCache(Request $request, KernelInterface $kernel, string $uri): Response
    {
        if ($kernel->getEnvironment() === 'prod') {
            return new Response('KO', Response::HTTP_OK);
        }

        $store = (new class($kernel) extends HttpCache{})->getStore();
        $store->purge($request->getSchemeAndHttpHost() . '/' . $uri);

        return new Response('Done');
    }
}
