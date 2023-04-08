<?php
namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]
Class CategoryController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }

    #[Route('/new/', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted()) {
            $categoryRepository->save($category,true);
            return $this->redirectToRoute('category_index');

        }
        return $this->render("category/new.html.twig",['form'=>$form]);
    }

    #[Route('/show/{categoryName}',name:'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException("Aucune catégorie nommée $categoryName");
        }

        $programs = $programRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC'],
            3
        );

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
        ]);
    }

}
