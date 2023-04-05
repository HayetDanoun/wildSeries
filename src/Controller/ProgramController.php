<?php
namespace App\Controller;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

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

//    #[Route(
//        path: '/{id}/',
//        name: 'show',
//        methods: ['GET'],
//        requirements: [
//            'page'=>'\d+',
//        ]),
//    ]
    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $programRepository):Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );

        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{programId}/seasons/{seasonId}',name:'season_show')]
    public function showSeason(int $programId, int $seasonId,ProgramRepository $programRepository,SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);

        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }
        if(!$season) {
            throw $this->createNotFoundException(
                'The program no season with id : '. $seasonId .' found in program\'s table.'
            );
        }
        return $this->render("program/season_show.html.twig.", [
            'program' => $program,
            'season' => $season,
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