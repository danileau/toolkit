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
        $currentGewicht = $gewichtRepository->getLastGewicht($user);
        $lastSevenDaysGewicht = $gewichtRepository->getLastSevenGewicht($user);
        $lastSevenCompGewicht = $gewichtRepository->getLastSevenCompGewicht($user);

        //dd($lastSevenCompGewicht);
        //dd($CountlastSevenDaysGewicht);
        if (count($lastSevenDaysGewicht) <= 7) {

            $arranged_lastSeven = array($lastSevenDaysGewicht[0]['gewicht'], $lastSevenDaysGewicht[1]['gewicht'], $lastSevenDaysGewicht[2]['gewicht'], $lastSevenDaysGewicht[3]['gewicht'], $lastSevenDaysGewicht[4]['gewicht'], $lastSevenDaysGewicht[5]['gewicht'], $lastSevenDaysGewicht[6]['gewicht']);
            $avg_lastSeven = (!empty($arranged_lastSeven) ? array_sum($arranged_lastSeven) / count($arranged_lastSeven) : 0);

            $arranged_lastSevenComp = array($lastSevenCompGewicht[0]['gewicht'], $lastSevenCompGewicht[1]['gewicht'], $lastSevenCompGewicht[2]['gewicht'], $lastSevenCompGewicht[3]['gewicht'], $lastSevenCompGewicht[4]['gewicht'], $lastSevenCompGewicht[5]['gewicht'], $lastSevenCompGewicht[6]['gewicht']);
            $avg_lastSevenComp = (!empty($arranged_lastSevenComp) ? array_sum($arranged_lastSevenComp) / count($arranged_lastSevenComp) : 0);

            $lastSevenGewicht_percent = ($avg_lastSeven - $avg_lastSevenComp) / $avg_lastSeven * 100;

        } else {
            $avg_lastSeven = 'none';
            $avg_lastSevenComp = 'none';
            $lastSevenGewicht_percent = 'none';
        }

        if (empty($currentGewicht)) {
            $currentGewicht = 0;
        }
        $pal = $PALRepository->getLastPAL($user);
        if ($pal <= 2) {
            $pal = 0;
        }
        $pals = $PALRepository->getLastTwoPAL($user);

        if (count($pal) <= 2) {
            $pal_percent = ($pals[0]['value'] - $pals[1]['value']) / $pals[0]['value'] * 100;
        } else {
            $pal_percent = 'none';
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
        $eventDiagramCount = array_reverse($gewichtDiagramCount);
        $eventValueJSON = json_encode($eventDiagramCount);

        $event_m_data = $gewichtRepository->getDiagramMonthData($user);
        $bf_m_data = $gewichtRepository->getDiagramMonthBFData($user);

        foreach ($event_m_data as $key => $value) {
            $event_m_DiagramMonth[] = $key;
            if (empty($value)) {
                $value[] = "N/A";
            }
            $event_m_DiagramCount[] = $value[0];
        }
        $event_m_MonthJSON = json_encode($event_m_DiagramMonth);
        $event_m_ValueJSON = json_encode($event_m_DiagramCount);

        foreach ($bf_m_data as $key => $value) {
            $bf_m_DiagramMonth[] = $key;
            if (empty($value)) {
                $value[] = "N/A";
            }
            $bf_m_DiagramCount[] = $value[0];
        }
        $bf_m_MonthJSON = json_encode($bf_m_DiagramMonth);
        $bf_m_ValueJSON = json_encode($bf_m_DiagramCount);
        //dd($currentGewicht);
        $grundumsatz = 1 * $currentGewicht['gewicht'] * 24;
        $gesamtEnergieBedarf = $grundumsatz * $pal['value'];
        if ($gesamtEnergieBedarf != 0) {
            $aufbau = $gesamtEnergieBedarf + 300;
            $defizit = $gesamtEnergieBedarf - 300;
        }else{
            $aufbau = 0;
            $defizit = 0;
        }
        return $this->render('tools/index.html.twig', [
            'controller_name' => 'ToolsController',
            'current_gewicht' => $currentGewicht,
            'gewicht_avg_7' => $avg_lastSeven,
            'gewicht_percent' => $lastSevenGewicht_percent,
            'pal' => $pal['value'],
            'pal_percent' => $pal_percent,
            'aufbau' => $aufbau,
            'erhalt' => $gesamtEnergieBedarf,
            'defizit' => $defizit,
            'gewicht_m_month' => $event_m_MonthJSON,
            'gewicht_data' => $eventValueJSON,
            'gewicht_m_data' => $event_m_ValueJSON,
            'gewicht_bf_data' => $bf_m_ValueJSON
        ]);
    }
}
