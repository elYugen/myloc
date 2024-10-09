<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Items;
use App\Form\LoanFormType;
use App\Repository\ItemsRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoanController extends AbstractController
{
    #[Route('/loan/{id}', name: 'app_loan')]
    public function loan(Items $item, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, ItemsRepository $itemsRepository, $id): Response
    {
        $items = $itemsRepository->find($id);
        $loan = new Loan();
        $form = $this->createForm(LoanFormType::class, $loan);
        $form->handleRequest($request);


        // donner des point au proprio
        $owner = $item->getOwner();
        $category = $item->getCategory();
        $points = $category->getPointswin();

        // ajoute point
        $currentPoints = $owner->getPoints() ?? 0;
        $owner->setPoints($currentPoints + $points);

        if ($form->isSubmitted() && $form->isValid()) {

            $loan->setItem($item);
            $loan->setBorrower($this->getUser());

            $errors = $validator->validate($loan);
            if (count($errors) > 0) {
                // Handle validation errors
            }

            $entityManager->persist($loan);
            $entityManager->flush();

            return $this->redirectToRoute('app_items');
        }
        return $this->render('loan/loan.html.twig', [
            'form' => $form,
            'items' => $items,
        ]);
    }

    #[Route('/loan/return/{id}', name: 'app_loan_return', methods: ['POST'])]
    public function returnLoan(Loan $loan, EntityManagerInterface $entityManager): Response
    {
        // verifie si l'utilisateur est le loueur
        if ($loan->getBorrower() !== $this->getUser()) {
            throw $this->createAccessDeniedException('tu ne peux pas rendre ça');
        }

        // suppr l'emprunt
        $entityManager->remove($loan);
        $entityManager->flush();

        return $this->redirectToRoute('app_main'); 
    }
}
