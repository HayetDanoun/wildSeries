<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity; //pour param converter classe exterieur

#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(Program $program):Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{programId}/seasons/{seasonId}',name:'season_show')]
    #[Entity('program', options: ['mapping' => ['programId' => 'id']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Program $program,Season $season): Response
    {

        return $this->render("program/season_show.html.twig.", [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}/episode/{episodeId}',name:'episode_show')]
    #[Entity('program', options: ['mapping' => ['programId' => 'id']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[Entity('episode',options: ['mapping' => ['episodeId' => 'id']])]
    public function showEpiosde(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig',[
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/new',name:'new')]
    public function new() : Response
    {
        return $this->redirectToRoute('program_show', ['id' => 4]);
    }
}

//Response = reponse HTTP complète
//HTML, JSON