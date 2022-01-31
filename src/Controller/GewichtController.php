<?php

namespace App\Controller;

use App\Entity\Gewicht;
use App\Form\GewichtType;
use App\Repository\GewichtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/tools/gewicht")
 * @IsGranted("ROLE_USER")
 */
class GewichtController extends AbstractController
{
    /**
     * @Route("/", name="gewicht_index", methods={"GET"})
     */
    public function index(GewichtRepository $gewichtRepository, UserInterface $user): Response
    {
        return $this->render('gewicht/index.html.twig', [
            'gewichts' => $gewichtRepository->findAllFromUser($user->getId()),
        ]);
    }

    /**
     * @Route("/new", name="gewicht_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $gewicht = new Gewicht();
        $form = $this->createForm(GewichtType::class, $gewicht);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Gewicht $gewichtvalue */
            $gewichtvalue = $form->getData();
            $gewichtvalue->setTimestamp($gewichtvalue->getTimestamp());
            $gewichtvalue->setUser($user);

            $entityManager->persist($gewicht);
            $entityManager->flush();

            return $this->redirectToRoute('gewicht_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gewicht/new.html.twig', [
            'gewicht' => $gewicht,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gewicht_show", methods={"GET"})
     */
    public function show(Gewicht $gewicht): Response
    {
        $user = $this->getUser();
        if ($user->getId() != $gewicht->getUser()->getId()) {
            throw new AccessDeniedException('Zugriff verweigert');
        }
        return $this->render('gewicht/show.html.twig', [
            'gewicht' => $gewicht,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gewicht_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Gewicht $gewicht, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user->getId() != $gewicht->getUser()->getId()) {
            throw new AccessDeniedException('Zugriff verweigert');
        }
        $form = $this->createForm(GewichtType::class, $gewicht);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('gewicht_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gewicht/edit.html.twig', [
            'gewicht' => $gewicht,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gewicht_delete", methods={"POST"})
     */
    public function delete(Request $request, Gewicht $gewicht, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user->getId() != $gewicht->getUser()->getId()) {
            throw new AccessDeniedException('Zugriff verweigert');
        }
        if ($this->isCsrfTokenValid('delete'.$gewicht->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gewicht);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gewicht_index', [], Response::HTTP_SEE_OTHER);
    }
}
