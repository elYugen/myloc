<?php

namespace App\Controller;

use App\Repository\ItemsRepository;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile/myloan', name: 'app_profile_loan')]
    public function loan(LoanRepository $loanRepository): Response
    {
        $user = $this->getUser();
        $loans = $loanRepository->findBy(['borrower' => $user]);

        return $this->render('profile/loan.html.twig', [
            'loans' => $loans,
        ]);
    }

    #[Route('/profile/myitems', name: 'app_profile_items')]
    public function items(ItemsRepository $itemRepository): Response
    {
        $user = $this->getUser();
        $items = $itemRepository->findBy(['owner' => $user]);

        return $this->render('profile/item.html.twig', [
            'items' => $items,
        ]);
    }
}
