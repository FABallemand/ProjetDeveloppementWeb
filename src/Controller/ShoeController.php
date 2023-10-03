<?php

namespace App\Controller;

use App\Entity\Shoe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controller Shoe
 */
#[Route('/shoe')]
class ShoeController extends AbstractController
{
    #[Route('/', name: 'shoe_home', methods: ['GET'])]
    public function indexAction()
    {
        return $this->render('shoe/index.html.twig', [
            'controller_name' => 'CupboardController',
        ]);
    }

    /**
     * Lists all shoe entities.
     */
    #[Route('/list', name: 'shoe_list', methods: ['GET'])]
    #[Route('/index', name: 'shoe_index', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $shoes = $entityManager->getRepository(Shoe::class)->findAll();

        // dump($shoes);

        return $this->render(
            'shoe/list.html.twig',
            ['shoes' => $shoes]
        );
    }

    /**
     * Finds and displays a shoe entity.
     */
    #[Route('/{id}', name: 'shoe_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showAction(Shoe $shoe): Response
    {
        return $this->render(
            'shoe/show.html.twig',
            ['shoe' => $shoe]
        );
    }
}
