<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Base;
use AppBundle\Entity\Tables;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Contact;

class CalendarRepository
{
    
    /**
     * @var EntityRepository
     */
    private $repository;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositoryBase = $entityManager->getRepository(Base::class);
        $this->repositoryTable = $entityManager->getRepository(Tables::class);
        $this->repositoryContact = $entityManager->getRepository(Contact::class);
    }


    public function findSites()
    {
        $query = $this->repositoryBase->createQueryBuilder('b')
        ->join('AppBundle:BaseL', 'bl', 'WITH', 'b.cle = bl.cle')
        ->where('bl.clp=111');
        return $query->getQuery()->getResult();
    }

    public function findTypesRetraites($Tlien)
    {
        $query = $this->repositoryTable->createQueryBuilder('t')
        ->select('t.tref', 't.tlib', 't.tmemo')
        ->where('t.tlien = :tlien')
        ->orderBy('t.trang')
        ->setParameter(':tlien', $Tlien);
        return $query->getQuery()->getResult();
    }

    public function findCalendrier($dateDeb, $divAct, $siteAct, $TypLcal)
    {
        $imbriquedQuery = $this->repositoryContact->createQueryBuilder('c')
        ->join('AppBundle:CalL', 'cl', 'WITH', 'c.codco = cl.lcal')
        ->where('cl.typlcal=:TypLcal')
        ->setParameter(':TypLcal', $TypLcal);
        
        $mainQuery =$this->repositoryContact->createQueryBuilder('c')
        // ->from(
        //     $this->repositoryContact->createQueryBuilder('c')
        //     ->join('AppBundle:CalL', 'cl', 'WITH', 'c.codco = cl.lcal')
        //     ->where('cl.typlcal = :TypLcal')
        //     ->setParameter(':TypLcal', $TypLcal)
        //     )
        ->join('AppBundle:Calendrier', 'ca', 'with', 'ca.codcal = cl.codcal')
        ->join('AppBundle:Activite', 'a', 'with', 'a.codact = ca.codb')
        ->where('datdeb >= :dateDeb')
        ->andWhere('divact = :divAct')
        ->andWhere('sitact = :siteAct')
        ->orderBy('datdeb')
        ->setParameter(':dateDeb', $dateDeb, ':divAct', $divAct, ':siteAct', $siteAct);

        return $mainQuery->getQuery()->getResult();
    }

    public function insertContact(Contact $contact)
    {
        $sql ="";
        return $sql;
    }

    public function updateContact(Contact $contact)
    {
        $sql ="";
        return $sql;
    }

    public function updateInscrCount(Contact $contact, CalL $calL)
    {
        $sql ="";
        return $sql;
    }

    public function inscrGroup(Contact $contact, ContactL $contactL)
    {
        $sql="";
        return $sql;
    }
}
