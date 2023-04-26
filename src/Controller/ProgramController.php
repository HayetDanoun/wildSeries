<?php
namespace App\Controller;

use App\Form\ProgramType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProgramRepository;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity; //pour param converter classe exterieur

use Symfony\Component\HttpFoundation\Request;

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
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        $errors = [];
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('poster')->getData();
            if ($imageFile) {
                // Vérification de la taille de l'image (inférieur à 1Mo)
                if ($imageFile->getSize() > 1000000) {
                    $errors[] = 'La taille de l\'image ne peut pas dépasser 1 Mo.';
                }
                // Vérification de l'extension de l'image
                if (!in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $errors[] = 'Le format de l\'image doit être jpg, jpeg, png, gif ou webp.';
                }

                $uploadDir = '/uploads/program/poster/';
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $newFilename = $uploadDir . basename($newFilename);

                try {
                    $imageFile->move(
                        //road in the repertory config/serviecs.yaml
                        $this->getParameter('program_poster_directory'),
                        $newFilename
                    );
                    $program->setPoster($newFilename);

                } catch (FileException $e) {
                            $errors[] = 'Une erreur est survenue lors de l\'upload de l\'image.';
                        }
            }
            if (empty($errors)) {
                $this->addFlash('success', 'The new program has been created');
                $programRepository->save($program, true);
                return $this->redirectToRoute('program_index');
            }
        }

        return $this->render("program/new.html.twig", [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }
}

//Response = reponse HTTP complète
//HTML, JSON