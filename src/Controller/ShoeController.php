<?php

namespace App\Controller;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use App\Entity\Member;
use App\Form\ShoeType;
use App\Repository\ShoeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Shoe Controller
 */
#[Route('/shoe')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ShoeController extends AbstractController
{

    #[Route('/', name: 'app_shoe_index', methods: ['GET'])]
    public function index(ShoeRepository $shoeRepository): Response
    {
        $shoes = array();

        if ($this->isGranted('ROLE_ADMIN')) {
            $shoes = $shoeRepository->findAll();
        } else {
            // Retrieve shoes stored inside cupboard owned by current member
            // Avoid creating a query builder...
            // $cupboards = $this->getUser()->getMember()->getCupboards();
            // foreach ($cupboards as $cupboard) {
            //     $shoes = array_merge($shoes, $cupboard->getShoes()->toArray());
            // }
            // $shoes = $shoeRepository->findBy(['cupboard' => $shoes]);

            $user = $this->getUser();
            $member = null;
            if ($user) {
                $member = $user->getMember();
            }
            if ($member) {
                $shoes = $shoeRepository->findByMember($member);
            }
        }

        return $this->render('shoe/index.html.twig', [
            'shoes' => $shoes,
        ]);
    }

    #[Route('/new', name: 'app_shoe_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Change content-type according to image's
            $imagefile = $shoe->getImageFile();
            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $shoe->setContentType($mimetype);
            }
            $entityManager->persist($shoe);
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully bought!');

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/new.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/newincupboard/{id}', name: 'app_shoe_newincupboard', methods: ['GET', 'POST'])]
    public function newInCupboard(Request $request, EntityManagerInterface $entityManager, Cupboard $cupboard): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $cupboard->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's cupboard!");
        }

        $shoe = new Shoe();
        // already set a cupboard, no need to add that field in the form (in ShoeType)
        $shoe->setCupboard($cupboard);

        $form = $this->createForm(ShoeType::class, $shoe, ['display_cupboard' => false,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Change content-type according to image's
            $imagefile = $shoe->getImageFile();
            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $shoe->setContentType($mimetype);
            }
            $entityManager->persist($shoe);
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully stored inside cupboard!');

            return $this->redirectToRoute('app_cupboard_show', ['id' => $cupboard->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/newincupboard.html.twig', [
            'cupboard' => $cupboard,
            'shoe' => $shoe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_shoe_show', methods: ['GET'])]
    public function show(Shoe $shoe): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $shoe->getCupboard()->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's shoe from his cupboard!");
        }

        return $this->render('shoe/show.html.twig', [
            'shoe' => $shoe,
        ]);
    }

    /**
     * Mark shoes in the user's session -> NOT USED IN THE WEB APP (see shelf marking instead)
     */
    #[Route('/mark/{id}', name: 'app_shoe_mark', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function markAction(Request $request, Shoe $shoe): Response
    {
        // Retrieve marked shoes array from session
        $markedShoes = $request->getSession()->get('marked_shoes');
        dump($markedShoes);
        if (!is_array($markedShoes)) {
            $markedShoes = array();
        }

        // dump($shoe);
        // dump($markedShoes);

        $id = $shoe->getId();
        // if the shoe id is not in the array of marked shoes, add it to the array
        if (!in_array($id, $markedShoes)) {
            $markedShoes[] = $id;
        } else
        // else, remove it from the array
        {
            // substract two arrays
            $markedShoes = array_diff($markedShoes, array($id));
        }

        // Update marked shoes array in session
        $request->getSession()->set('marked_shoes', $markedShoes);

        return $this->redirectToRoute(
            'app_shoe_show',
            ['id' => $shoe->getId()]
        );
    }

    #[Route('/{id}/edit', name: 'app_shoe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $shoe->getCupboard()->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot edit another member's shoe!");
        }

        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('message', 'Shoe successfully cleaned!');

            return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shoe/edit.html.twig', [
            'shoe' => $shoe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shoe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Shoe $shoe, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $member = null;
        if ($user) {
            $member = $user->getMember();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($member == $shoe->getCupboard()->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot delete another member's shoe!");
        }

        // if ($shoe->isCompleted() == false) {
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }

        if ($this->isCsrfTokenValid('delete' . $shoe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shoe);
            $entityManager->flush();

            $this->addFlash('delete', 'Shoe successfully thrown...');
        }

        return $this->redirectToRoute('app_shoe_index', [], Response::HTTP_SEE_OTHER);
    }
}
