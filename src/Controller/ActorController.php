<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name:'actor_')]
class ActorController extends AbstractController
{
    #[Route('/{id}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

//    #[Route('/{id}', name: 'show')]
//    public function show(int $id,ActorRepository $actorRepository ): Response
//    {
//        $actor = $actorRepository->findOneBy(['id'=>$id]);
//        return $this->render('actor/show.html.twig', [
//            'actor' => $actor,
//        ]);
//    }
}
