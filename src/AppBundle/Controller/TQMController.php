<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Task;
use AppBundle\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TQMController extends Controller
{
    /**
     * @Route("/pomysly", name="view_ideas")
     */
    public function listAction(EntityManagerInterface $em, Request $request)
    {
        $user = $this->getUser();

        $tasks = $em->getRepository(Task::class)->findAll();
        $comments = $em->getRepository(Comment::class)->findBy([], ['createdAt' => 'DESC']);

        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $comment Comment
             */
            $comment = $form->getData();
            $comment->setUser($user);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('view_ideas');
        }

        return $this->render('tqm/news.html.twig', [
            'tasks' => $tasks,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }
}
