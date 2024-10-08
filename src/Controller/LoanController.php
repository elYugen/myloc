<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Items;
use App\Repository\ItemsRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoanController extends AbstractController
{
    #[Route('/loan/{id}', name: 'app_loan')]
    public function loan(Items $item, Request $request, EntityManagerInterface $entityManager): Response
    {
        $loan = new Loan();
        $loan->setItem($item);
        $loan->setBorrower($this->getUser());
        $loan->setStartDate(new \DateTime());
        $loan->setEndDate((new \DateTime())->modify('+7 days'));

        // donner des point au proprio
        $owner = $item->getOwner();
        $category = $item->getCategory();
        $points = $category->getPointswin();

        // ajoute point
        $currentPoints = $owner->getPoints() ?? 0;
        $owner->setPoints($currentPoints + $points);

        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('app_items');
    }


}
