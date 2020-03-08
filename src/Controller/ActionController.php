<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\Form\CommentType;
use App\Form\EditStatusType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActionController extends AbstractController
{

    /**
     * @Route("delete/{task}", name="delete")
     */
    public function removeTask(Task $task, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute("main");
    }

    /**
     * @Route("deleteComment/{comment}", name="deleteComment")
     */
    public function removeComment(Comment $comment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute("main");
    }

    /**
     * @Route("show/{id}", name="show")
     */
    public function showTask($id)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        $comments = $task->getComments();


        if (!$task) {
            throw $this->createNotFoundException(
                'No quiz found for id ' . $id
            );
        }

        return $this->render('edit_task/show_task.html.twig', [
            'controller_name' => 'EditQuizController',
            'task' => $task,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("add/comment/{id}", name="add_comment")
     */
    public function addComment($id, Request $request)
    {

        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        $comment = new Comment();

        $task->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $comment = $form->getData();

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute("main");
        }

        return $this->render('edit_task/add_comment.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("task/edit{id}", name="edit_status")
     */
    public function editQuiz(Task $task, Request $request)
    {

            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(EditStatusType::class, $task);
            $form -> handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $task = $form->getData();

                $em->persist($task);
                $em->flush();

                return $this->redirectToRoute("main");
            }

            return $this->render('edit_task/edit_status.html.twig', [
                'form' => $form->createView()
            ]);

    }

}
