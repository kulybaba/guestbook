<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Entity\Photo;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    /**
     * @Route("/conferences", name="conferences")
     */
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/conference/{slug}", name="conference_show")
     */
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager,
        string $photoDir,
        UserRepository $userRepository
    ): Response {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentsPaginator($conference, $offset);

        $comment = new Comment();
        $comment->setConference($conference);
        $comment->setAuthor($userRepository->find(1));
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($file = $form['photo']->getData()) {
                $extension = $file->guessExtension();
                $fileName = bin2hex(random_bytes(10)) . '.' . $extension;
                $photo = new Photo();
                $photo->setFileName($fileName);
                $photo->setExtension($extension);
                $photo->setUrl(Photo::DIR_NAME . $fileName);

                try {
                    $file->move($photoDir, $fileName);
                } catch (FileException $e) {
                    //
                }

                $comment->setPhoto($photo);
                $entityManager->persist($photo);
            }

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('conference_show', [
                'slug' => $conference->getSlug(),
            ]);
        }

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - Comment::COMMENTS_LIMIT,
            'next' => min(count($paginator), $offset + Comment::COMMENTS_LIMIT),
            'commentForm' => $form->createView(),
        ]);
    }
}
