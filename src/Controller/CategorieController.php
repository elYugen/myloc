<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategoriesRepository $categoriesRepository,): Response
    {
        $category = $categoriesRepository->findAll();

        return $this->render('categorie/index.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_id')]
    public function id(CategoriesRepository $categoriesRepository, $id): Response
    {
        $category = $categoriesRepository->find($id);

        return $this->render('categorie/information.html.twig', [
            'category' => $category,
        ]);
    }
}
