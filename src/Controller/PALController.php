<?php

namespace App\Controller;

use App\Entity\PAL;
use App\Form\PALType;
use App\Repository\PALRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/pal")
 * @IsGranted("ROLE_USER")
 */
class PALController extends AbstractController
{
    /**
     * @Route("/", name="pal_index", methods={"GET"})
     */
    public function index(PALRepository $pALRepository): Response
    {
        return $this->render('pal/index.html.twig', [
            'pals' => $pALRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pal_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $pAL = new PAL();
        $form = $this->createForm(PALType::class, $pAL);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var PAL $palvalue */
            $palvalue = $form->getData();
            $palvalue->setTimestamp(new \DateTime());
            $palvalue->setUserId($user);

            /* (((fieldname12*0.95)+(fieldname13*1.2)+(fieldname15*1.45)+(fieldname16*1.65)+(fieldname14*1.85)+(fieldname17*2.2))\/24)
            Schlaf*0.95 - Ausschliesslich Sitzend *1.2 - sitzende Tätigkeit * 1.45 - Stehend * 1.65 -  */
            $gesamtwert = (($palvalue->getSchlaf()*0.95) + ($palvalue->getLiegend() * 1.2) + ($palvalue->getSitzend() * 1.45) + ($palvalue->getGehend() * 1.65) + ($palvalue->getStehend() * 1.85) + ($palvalue->getSport() *2.2))/24;
            $palvalue->setValue($gesamtwert);
            $entityManager->persist($pAL);
            $entityManager->flush();

            return $this->redirectToRoute('pal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pal/new.html.twig', [
            'pal' => $pAL,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pal_show", methods={"GET"})
     */
    public function show(PAL $pAL): Response
    {
        return $this->render('pal/show.html.twig', [
            'pal' => $pAL,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pal_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PAL $pAL, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PALType::class, $pAL);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $values = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('pal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pal/edit.html.twig', [
            'pal' => $pAL,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pal_delete", methods={"POST"})
     */
    public function delete(Request $request, PAL $pAL, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pAL->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pAL);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pal_index', [], Response::HTTP_SEE_OTHER);
    }
}
