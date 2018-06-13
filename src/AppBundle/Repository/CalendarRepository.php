<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Base;
use AppBundle\Entity\Tables;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\contact;

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
        $query = $this->repository->createQueryBuilder('b')
        ->join('AppBundle:BaseL', 'bl', 'WITH', 'b.cle = bl.cle')
        ->where('bl.clp=111');
        return $query->getQuery()->getResult();
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
