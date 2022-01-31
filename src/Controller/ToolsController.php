<?php

namespace App\Controller;

use App\Repository\GewichtRepository;
use App\Repository\PALRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ToolsController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @IsGranted("ROLE_USER")
     */
    public function index(GewichtRepository $gewichtRepository, PALRepository $PALRepository, UserInterface $user): Response
    {

        /**
         * Switch Logic for Gewicht-Values
         */
        $all_Gewicht_user = $gewichtRepository->findAllFromUser($user->getId());

        global $count_all_gewicht;
        $count_all_gewicht= count($all_Gewicht_user);

        switch(true){
            case $count_all_gewicht == 0:

                $currentGewicht = 0;
                $lastSevenDaysGewicht = 0;
                $lastSevenCompGewicht = 0;
                $avg_lastSeven = 0;
                $avg_lastSevenComp = 0;
                $lastSevenGewicht_percent = 0;
                break;
            case ($count_all_gewicht < 7):
                $Gewicht = $gewichtRepository->getLastGewicht($user);
                $currentGewicht = $Gewicht["gewicht"];
                $lastSevenDaysGewicht = 0;
                $lastSevenCompGewicht = 0;
                $avg_lastSeven = 0;
                $avg_lastSevenComp = 0;
                $lastSevenGewicht_percent = 0;
                break;
            default:

                $Gewicht = $gewichtRepository->getLastGewicht($user);
                $currentGewicht = $Gewicht["gewicht"];
                $lastSevenDaysGewicht = $gewichtRepository->getLastSevenGewicht($user);
                $lastSevenCompGewicht = $gewichtRepository->getLastSevenCompGewicht($user);

                $arranged_lastSeven = array($lastSevenDaysGewicht[0]['gewicht'], $lastSevenDaysGewicht[1]['gewicht'], $lastSevenDaysGewicht[2]['gewicht'], $lastSevenDaysGewicht[3]['gewicht'], $lastSevenDaysGewicht[4]['gewicht'], $lastSevenDaysGewicht[5]['gewicht'], $lastSevenDaysGewicht[6]['gewicht']);
                $avg_lastSeven = (!empty($arranged_lastSeven) ? array_sum($arranged_lastSeven) / count($arranged_lastSeven) : 0);

                $arranged_lastSevenComp = array($lastSevenCompGewicht[0]['gewicht'], $lastSevenCompGewicht[1]['gewicht'], $lastSevenCompGewicht[2]['gewicht'], $lastSevenCompGewicht[3]['gewicht'], $lastSevenCompGewicht[4]['gewicht'], $lastSevenCompGewicht[5]['gewicht'], $lastSevenCompGewicht[6]['gewicht']);
                $avg_lastSevenComp = (!empty($arranged_lastSevenComp) ? array_sum($arranged_lastSevenComp) / count($arranged_lastSevenComp) : 0);

                $lastSevenGewicht_percent = ($avg_lastSeven - $avg_lastSevenComp) / $avg_lastSeven * 100;
                break;
        }


        /**
         * Switch Logic for PAL Values
         */
        $all_PAL_user = $PALRepository->findAllFromUser($user->getId());
        switch(count($all_PAL_user)){
            case 0:
                $last_PAL["value"] = 0;

            break;
            case 1:
                $last_PAL = $PALRepository->getLastPAL($user->getId());
            break;
            default:
                $last_PAL = $PALRepository->getLastPAL($user->getId());
                $lastTwo_PAL = $PALRepository->getLastTwoPAL($user->getId());
            break;
        }

        /**
         * Gewichtsdaten für die Diagramme aufbereiten
         */
        $gewicht_data = $gewichtRepository->getDiagramData($user);
        foreach ($gewicht_data as $key => $value) {
            $gewichtDiagramMonth[] = strftime("%B %Y", strtotime($key . "-01"));;
            $gewichtDiagramCount[] = $value;
        }
        $eventDiagramMonth = array_reverse($gewichtDiagramMonth);
        $gewichtDiagramCount = array_reverse($gewichtDiagramCount);
        $gewichtValueJSON = json_encode($gewichtDiagramCount);

        $gewicht_m_data = $gewichtRepository->getDiagramMonthData($user);
        $bf_m_data = $gewichtRepository->getDiagramMonthBFData($user);

        foreach ($gewicht_m_data as $key => $value) {
            $gewicht_m_DiagramMonth[] = $key;
            if (empty($value)) {
                $value[] = "N/A";
            }
            $gewicht_m_DiagramCount[] = $value[0];
        }
        $gewicht_m_MonthJSON = json_encode($gewicht_m_DiagramMonth);
        $gewicht_m_ValueJSON = json_encode($gewicht_m_DiagramCount);

        foreach ($bf_m_data as $key => $value) {
            $bf_m_DiagramMonth[] = $key;
            if (empty($value)) {
                $value[] = "N/A";
            }
            $bf_m_DiagramCount[] = $value[0];
        }
        $bf_m_MonthJSON = json_encode($bf_m_DiagramMonth);
        $bf_m_ValueJSON = json_encode($bf_m_DiagramCount);

        if (isset($Gewicht['gewicht']) && isset($last_PAL['value'])) {
            $grundumsatz = 1 * $Gewicht['gewicht'] * 24;
            $gesamtEnergieBedarf = $grundumsatz * $last_PAL['value'];
            if ($gesamtEnergieBedarf != 0) {
                $aufbau = $gesamtEnergieBedarf + 300;
                $defizit = $gesamtEnergieBedarf - 300;
            } else {
                $aufbau = 0;
                $defizit = 0;
            }
        }else{
            $aufbau = 0;
            $defizit = 0;
            $gesamtEnergieBedarf = 0;
        }
        return $this->render('tools/index.html.twig', [
            'controller_name' => 'ToolsController',
            'current_gewicht' => $currentGewicht,
            'gewicht_avg_7' => $avg_lastSeven,
            'gewicht_percent' => $lastSevenGewicht_percent,
            'pal' => $last_PAL['value'],
            'aufbau' => $aufbau,
            'erhalt' => $gesamtEnergieBedarf,
            'defizit' => $defizit,
            'gewicht_m_month' => $gewicht_m_MonthJSON,
            'gewicht_data' => $gewichtValueJSON,
            'gewicht_m_data' => $gewicht_m_ValueJSON,
            'gewicht_bf_data' => $bf_m_ValueJSON
        ]);
    }
}
