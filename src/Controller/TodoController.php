<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Todo;
use App\Form\TodoType;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo_list')]
    public function listAction(TodoRepository $todoRepository): Response
    {
        $todo = $todoRepository->findAll();
        return $this->render('todo/index.html.twig', [
            'todos' => $todo,
        ]);
    }
    #[Route('/todo/details/{id}', name: 'todo_details')]
    public function detailsAction($id, TodoRepository $todoRepository): Response {
        $todo = $todoRepository->find($id);
        return $this -> render('todo/details.html.twig', [          
            'todos' => $todo, 
        ]); 
    }
    
    #[Route('/todo/delete/{id}', name:'todo_delete')]
    public function deleteAction($id, TodoRepository $todoRepository)
    {   
        $todo = $todoRepository->find($id);
        $todoRepository->remove($todo,true);
        $this->addFlash(
            'error',
            'Todo deleted'
        );
        
        return $this->redirectToRoute('todo_list');
    }

    // #[Route('/todo/create', name:'todo_create', methods:['GET','POST'])]
    // public function createAction(Request $request)
    // {
    //     $todo = new Todo();
    //     $form = $this->createForm(TodoType::class, $todo);
        
    //     if ($this->saveChanges($form, $request, $todo)) {
    //         $this->addFlash(
    //             'notice',
    //             'Todo Added'
    //         );
            
    //         return $this->redirectToRoute('todo_list');
    //     }
        
    //     return $this->render('todo/create.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
    // public function saveChanges($form, $request, $todo)
    // {
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $todo->setName($request->request->get('todo')['name']);
    //         $todo->setCategory($request->request->get('todo')['category']);
    //         $todo->setDescription($request->request->get('todo')['description']);
    //         $todo->setPriority($request->request->get('todo')['priority']);
    //         $todo->setDueDate(\DateTime::createFromFormat('Y-m-d', $request->request->get('todo')['due_date']));
    //         // $em = "Haizz";//$this->getDoctrine()->getManager();
    //         // $em->persist($todo);
    //         // $em->flush();
            
    //         return true;
    //     }
    //     return false;
    // }
    #[Route('/todo/create', name:'todo_create', methods:['GET','POST'])]
    public function createAction(Request $request, TodoRepository $todoRepo): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepo->add($todo, true);
            return $this->redirectToRoute('todo_list');
        }
        
        return $this->render('todo/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    
}
