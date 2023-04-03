<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program',name: 'program_')]
class ProgramController extends AbstractController
{
//  #[Route('/program/', name: 'program_index')]
    #[Route('/',name:'index')]
    public function index(): Response{
        return $this->render('program/index.html.twig',['hello' => 'Bonsoir Paris'],);
    }

    #[Route(
        path: '/{id}/',
        name: 'show',
        methods: ['GET'],
        requirements: [
            'page'=>'\d+',
        ]),
    ]
    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig',['id'=>$id]);
    }

    #[Route('/new',name:'new')]
    public function new() : Reponse
    {
        return $this->redirectToRoute('program_show', ['id' => 4]);
    }
}

//Response = reponse HTTP complète
//HTML, JSON