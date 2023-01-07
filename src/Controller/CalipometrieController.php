<?php

namespace App\Controller;

use App\Entity\Calipometrie;
use App\Form\CalipometrieType;
use App\Form\Calipometrie3Type;
use App\Repository\CalipometrieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/tools/calipometrie")
 * @IsGranted("ROLE_USER")
 */
class CalipometrieController extends AbstractController
{
    #[Route('/', name: 'calipometrie_index', methods: ['GET'])]
    public function index(CalipometrieRepository $calipometrieRepository, UserInterface $user): Response
    {
        return $this->render('calipometrie/index.html.twig', [
            'calipometries' => $calipometrieRepository->findAllFromUser($user),
        ]);
    }

    #[Route('/new', name: 'calipometrie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $calipometrie = new Calipometrie();
        $form = $this->createForm(CalipometrieType::class, $calipometrie);
        $form->handleRequest($request);
        
        $gender = $user->getGender();
        
        $birthday = $user->getBirthdate();
        $today = date("Y-m-d");
        $diff = date_diff(date_create($birthday->format('Y-m-d')), date_create($today));
        $alter = $diff->format('%y');
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data->setTimestamp(new \DateTime());
            $data->setUser($user);
            if ($data->getAchsel() != null and $data->getHuefte() != null and $data->getTrizeps() != null and $data->getRuecken() != null and $data->getBauch() != null and $data->getBrust() != null and $data->getBein() != null){
                $sum7 = $data->getBauch() + $data->getBrust() + $data->getHuefte() + $data->getTrizeps() + $data->getBein() + $data->getAchsel() + $data->getRuecken();    
            }else{
                $sum7 = null;
            }
    
            if ($gender == 'male' and ($data->getBauch() != null or $data->getBrust() != null or $data->getBein() != null)){
                $sum3m = $data->getBauch() + $data->getBrust() + $data->getBein();
                //https://de.wikipedia.org/wiki/Calipometrie        
                //7-Falten-Formel nach Jackson & Pollock - Source: Jackson, Pollock und Ward: Generalized equations for predicting body density of women
                if ($sum7 != null){
                    $BFcalcMen7 = ((4.95/(1.112 - ((434.99 * 0.000001) * $sum7) + ((0.55 * 0.000001) * $sum7 * $sum7) - (288.26 * 0.000001) * $alter)) - 4.5) * 100;
                }else{
                    $BFcalcMen7 = null;
                }
                $data->setBodyfat($BFcalcMen7);

            }elseif($gender == 'female' and ($data->getBauch() != null or $data->getBrust() != null or $data->getBein() != null)){
                if ($sum7 != null){
                    $BFcalcWomen7 = ((4.95/(1.097 - ((469.71 * 0.000001) * $sum7) + ((0.56 * 0.000001) * $sum7 * $sum7) - (128.28 * 0.000001) * $alter)) - 4.5) * 100;
                }else{
                    $BFcalcWomen7 = null;
                }
                $data->setBodyfat($BFcalcWomen7);
                
            } else{
                print_r("Bitte wählen Sie für die Berechnungen ein binäres Geschlecht und stellen Sie sicher, dass mindestens die Felder ausgefüllt sind.");
            }            
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('calipometrie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calipometrie/new.html.twig', [
            'calipometrie' => $calipometrie,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'calipometrie_show', methods: ['GET'])]
    public function show(Calipometrie $calipometrie): Response
    {
        return $this->render('calipometrie/show.html.twig', [
            'calipometrie' => $calipometrie,
        ]);
    }

    #[Route('/{id}/edit', name: 'calipometrie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calipometrie $calipometrie, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $form = $this->createForm(CalipometrieType::class, $calipometrie);
        $gender = $user->getGender();
        $birthday = $user->getBirthdate();
        $today = date("Y-m-d");
        $diff = date_diff(date_create($birthday->format('Y-m-d')), date_create($today));
        $alter = $diff->format('%y');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->getAchsel() != null and $data->getHuefte() != null and $data->getTrizeps() != null and $data->getRuecken() != null and $data->getBauch() != null and $data->getBrust() != null and $data->getBein() != null){
                $sum7 = $data->getBauch() + $data->getBrust() + $data->getHuefte() + $data->getTrizeps() + $data->getBein() + $data->getAchsel() + $data->getRuecken();    
            }else{
                $sum7 = null;
            }
    
            if ($gender == 'male' and ($data->getBauch() != null or $data->getBrust() != null or $data->getBein() != null)){
                $sum3m = $data->getBauch() + $data->getBrust() + $data->getBein();
                //https://de.wikipedia.org/wiki/Calipometrie        
                //7-Falten-Formel nach Jackson & Pollock - Source: Jackson, Pollock und Ward: Generalized equations for predicting body density of women
                if ($sum7 != null){
                    $BFcalcMen7 = ((4.95/(1.112 - ((434.99 * 0.000001) * $sum7) + ((0.55 * 0.000001) * $sum7 * $sum7) - (288.26 * 0.000001) * $alter)) - 4.5) * 100;
                }else{
                    $BFcalcMen7 = null;
                }
                $BFcalcMen3 = ((4.95/(1.10938 - (0.0008267 * $sum3m) + (0.0000016 * $sum3m * $sum3m) - (0.0002574 * $alter))) - 4.5) * 100;
    
                if($BFcalcMen7 != null){
    
                    $data->setBodyfat($BFcalcMen7);
                }else{
                    $data->setBodyfat($BFcalcMen3);
                }
            }elseif($gender == 'female' and ($data->getBauch() != null or $data->getBrust() != null or $data->getBein() != null)){
                $sum3w = $data->getBauch() + $data->getHuefte() + $data->getTrizeps();
                if ($sum7 != null){
                    $BFcalcWomen7 = ((4.95/(1.097 - ((469.71 * 0.000001) * $sum7) + ((0.56 * 0.000001) * $sum7 * $sum7) - (128.28 * 0.000001) * $alter)) - 4.5) * 100;
                }else{
                    $BFcalcWomen7 = null;
                }
                
                if($BFcalcWomen7 != null){
                    $data->setBodyfat($BFcalcWomen7);
                }else{
                    $data->setBodyfat($BFcalcWomen7);
                }
            } else{
                print_r("Bitte wählen Sie für die Berechnungen ein binäres Geschlecht und stellen Sie sicher, dass mindestens die Werte Bauch, Brust und Bein ausgefüllt sind.");
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('calipometrie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calipometrie/edit.html.twig', [
            'calipometrie' => $calipometrie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'calipometrie_delete', methods: ['POST'])]
    public function delete(Request $request, Calipometrie $calipometrie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calipometrie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calipometrie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calipometrie_index', [], Response::HTTP_SEE_OTHER);
    }


}
