<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavbarController extends AbstractController
{
    public function navbar(CategoryRepository $categoryRepository)
    {
        $categories= $categoryRepository->findAllName();

        return $this->render('_navbar.html.twig', [
            'categories' => $categories,
        ]);
    }

}
