<?php

namespace App\Controller;

use App\Entity\Items;
use App\Form\ItemsFormType;
use App\Repository\ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ItemsController extends AbstractController
{
    #[Route('/items', name: 'app_items')]
    public function index(ItemsRepository $itemsRepository): Response
    {
        $items = $itemsRepository->findAll();

        return $this->render('items/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/items/add', name: 'app_items_add')]
    public function addItems(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/')] string $imageDirectory): Response
    {
        $item = new Items();
        $form = $this->createForm(ItemsFormType::class, $item);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image) {
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFileName);
                } catch (FileException $e) {

                }   

                $item->setImage($newFileName);
            }
            $item->setOwner($this->getUser());
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_mainlogged');
        }
        return $this->render('items/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/items/{id}', name: 'app_items_information')]
    public function info(ItemsRepository $itemsRepository, $id): Response
    {
        $items = $itemsRepository->find($id);

        return $this->render('items/information.html.twig', [
            'items' => $items,
        ]);
    }
}
