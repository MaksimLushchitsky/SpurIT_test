<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request)
    {
        $task = new Task();

        $comment = new Comment();

        $task->addComment($comment);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $task->getTask();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("main");
        }

        return $this->render('admin/index.html.twig',
            array('form' => $form->createView(),
            ));
    }
}
