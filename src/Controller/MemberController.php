<?php

namespace App\Controller;

use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controller Member
 */
#[Route('/member')]
class MemberController extends AbstractController
{
    #[Route('/', name: 'member_home', methods: ['GET'])]
    public function indexAction()
    {
        return $this->render('member/index.html.twig', [
            'controller_name' => 'CupboardController',
        ]);
    }

    /**
     * Lists all member entities.
     */
    #[Route('/list', name: 'member_list', methods: ['GET'])]
    #[Route('/index', name: 'member_index', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $members = $entityManager->getRepository(Member::class)->findAll();

        // dump($members);

        return $this->render(
            'member/list.html.twig',
            ['members' => $members]
        );
    }

    /**
     * Finds and displays a member entity.
     */
    #[Route('/{id}', name: 'member_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showAction(Member $member): Response
    {
        return $this->render(
            'member/show.html.twig',
            ['member' => $member]
        );
    }
}
