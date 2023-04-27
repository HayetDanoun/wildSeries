<?php
namespace App\Controller;

use App\Form\ProgramType;
use App\Service\ProgramDuration;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProgramRepository;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity; //pour param converter classe exterieur

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/show/{slug}', name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration):Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/{programId}/season/{seasonId}',name:'season_show')]
    #[Entity('program', options: ['mapping' => ['programId' => 'id']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Program $program,Season $season): Response
    {

        return $this->render("program/season_show.html.twig", [
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



    #[Route('/new', name: 'program_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, ProgramRepository $programRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actors = $form->get('actors')->getData();
            $program->setActors($actors);

            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $programRepository->save($program, true);

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle())->lower();
            $program->setSlug($slug);
            $actors = $form->get('actors')->getData();
            $program->setActors($actors);
            $programRepository->save($program, true);
            $this->addFlash('success','Edit the program ' . $program->getTitle() . ' has been edited');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger','The new episode ' . $program->getTitle() . ' has been deleted');
            $programRepository->remove($program, true);
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }


}