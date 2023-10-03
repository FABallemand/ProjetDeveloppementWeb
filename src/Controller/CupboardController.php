<?php

namespace App\Controller;

use App\Entity\Cupboard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controller Cupboard
 */
#[Route('/cupboard')]
class CupboardController extends AbstractController
{
    #[Route('/', name: 'cupboard_home', methods: ['GET'])]
    public function indexAction()
    {
        return $this->render('cupboard/index.html.twig', [
            'controller_name' => 'CupboardController',
        ]);
    }

    /**
     * Lists all cupboard entities.
     */
    #[Route('/list', name: 'cupboard_list', methods: ['GET'])]
    #[Route('/index', name: 'cupboard_index', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $cupboards = $entityManager->getRepository(Cupboard::class)->findAll();

        // dump($cupboards);

        return $this->render(
            'cupboard/list.html.twig',
            ['cupboards' => $cupboards]
        );
    }

    /**
     * Finds and displays a cupboard entity.
     */
    #[Route('/{id}', name: 'cupboard_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showAction(Cupboard $cupboard): Response
    {
        return $this->render(
            'cupboard/show.html.twig',
            ['cupboard' => $cupboard]
        );
    }
}
