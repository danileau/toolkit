<?php

namespace App\Repository;

use App\Entity\Gewicht;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gewicht|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gewicht|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gewicht[]    findAll()
 * @method Gewicht[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GewichtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gewicht::class);
    }

    /**
     * @return Gewicht[] Returns all Gewichte ordered by the newest Timestamp
     */
    public function findAllFromUser($id)
    {
        return $this->findBy(array('user' => $id), array('timestamp' => 'DESC'));
    }

    public function getLastGewicht($id){
        return $this->createQueryBuilder('g')
            ->select('g.gewicht')
            ->where('g.user = :user')
            ->orderBy('g.timestamp', 'DESC')
            ->setMaxResults(1)
            ->setParameter('user', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastSevenGewicht($id){
        return $this->createQueryBuilder('g')
            ->select('g.gewicht')
            ->where('g.user = :user')
            ->orderBy('g.timestamp', 'DESC')
            ->setMaxResults(7)
            ->setParameter('user', $id)
            ->getQuery()
            ->getScalarResult();
    }

    public function getLastSevenCompGewicht($id){
        return $this->createQueryBuilder('g')
            ->select('g.gewicht')
            ->where('g.user = :user')
            ->orderBy('g.timestamp', 'DESC')
            ->setFirstResult(1)
            ->setMaxResults(7)
            ->setParameter('user', $id)
            ->getQuery()
            ->getScalarResult();
    }

    public function getDiagramData($id){
        $month = $this->getDiagramCurrentMonthJSON();
        foreach ($month as $key => $value) {
            $data[$value] = $this->getGewichtForMonth($id, $value);
        }
        return $data;
    }

    public function getDiagramMonthData($id)
    {
        $days = $this->getDiagramCurrentMonthJSON();
        foreach ($days as $key => $value) {
            $data[$value] = $this->getDailyDiagramMonth($id, $value);
        }
        return $data;
    }

    public function getDiagramMonthBFData($id)
    {
        $days = $this->getDiagramCurrentMonthJSON();
        foreach ($days as $key => $value) {
            $data[$value] = $this->getDailyDiagramBFMonth($id, $value);
        }
        return $data;
    }
    public function getDiagramCurrentMonthJSON()
    {
        //$month = "02";
        $month = date("m");
        $year = date("Y");

        $start_date = "01-" . $month . "-" . $year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $list[] = date('Y-m-d', $i);
        }

        return $list;
    }

    public function getGewichtForMonth($id, $month){
        //Year: $date[0], Month: $date[1]
        $date = explode('-', $month);

        $startDate = date("Y-m-d", strtotime($date[0]."-".$date[1]."-1"));
        $endDate = date("Y-m-t", strtotime($date[0]."-".$date[1]."-1"));
        $now = new \DateTime($endDate);
        // Jetzt + 1 Tag um einen gerade eben geschriebenen Eintrag, während demselben Tag auf dem Diagramm anzuzeigen;
        // "# <= :now" funktioniert am gleichen Tag nicht wie erwartet
        $now->modify('+1 day');
        $delay = new \DateTime($startDate);
        return $this->createQueryBuilder('ed')
            ->select('ed.gewicht')
            ->where('ed.user = :val')
            ->andWhere('ed.timestamp <= :now')
            ->andWhere('ed.timestamp = :delay')
            ->setParameter('val', $id)
            ->setParameter('now', $now)
            ->setParameter('delay', $delay)
            ->getQuery()
            ->getScalarResult();
    }

    public function getDailyDiagramMonth($id, $month)
    {
        $startDate = $month." 00:00:00";
        $endDate = $month." 23:59:59";
        return $this->createQueryBuilder('ed')
            ->select('ed.gewicht')
            ->where('ed.user = :val')
            ->andWhere('ed.timestamp >= :morning')
            ->andWhere('ed.timestamp <= :evening')
            ->setParameter('val', $id)
            ->setParameter('morning', $startDate)
            ->setParameter('evening', $endDate)
            ->getQuery()
            ->getSingleColumnResult();
    }
    public function getDailyDiagramBFMonth($id, $month)
    {
        $startDate = $month." 00:00:00";
        $endDate = $month." 23:59:59";
        return $this->createQueryBuilder('ed')
            ->select('ed.bf')
            ->where('ed.user = :val')
            ->andWhere('ed.timestamp >= :morning')
            ->andWhere('ed.timestamp <= :evening')
            ->setParameter('val', $id)
            ->setParameter('morning', $startDate)
            ->setParameter('evening', $endDate)
            ->getQuery()
            ->getSingleColumnResult();
    }
}
