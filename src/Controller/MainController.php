<?php

namespace App\Controller;

use App\Entity\Items;
use App\Repository\ItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ItemsRepository $itemsRepository): Response
    {
        $items = $itemsRepository->findAll();
        return $this->render('main/index.html.twig', [
            'items' => $items,
        ]);
    }
}
