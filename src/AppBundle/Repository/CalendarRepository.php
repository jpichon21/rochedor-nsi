<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Base;
use AppBundle\Entity\Tables;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Contact;
use Doctrine\ORM\Query\ResultSetMapping;

class CalendarRepository
{
    
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositoryBase = $entityManager->getRepository(Base::class);
        $this->repositoryTable = $entityManager->getRepository(Tables::class);
        $this->repositoryContact = $entityManager->getRepository(Contact::class);
        $this->entityManager = $entityManager;
    }
    

    public function findSites()
    {
        $query = $this->repositoryBase->createQueryBuilder('b')
        ->select('b.ref as abbr', 'b.titre as name')
        ->join('AppBundle:BaseL', 'bl', 'WITH', 'b.cle = bl.cle')
        ->where('bl.clp=111');
        return $query->getQuery()->getResult();
    }

    public function findEventTypes()
    {
        $query = $this->repositoryTable->createQueryBuilder('t')
        ->select('t.tref as abbr', 't.tlib name', 't.tmemo color')
        ->where('t.tlien = :tlien')
        ->orderBy('t.trang')
        ->setParameter(':tlien', 19);
        return $query->getQuery()->getResult();
    }

    public function findEvents()
    {
        $query = $this->entityManager->createQuery(
            'SELECT a.libact as event,
            c.datdeb AS dateIn,
            c.datfin AS dateOut,
            a.sitact as site,
            t.tref as typeAbbr, t.tlib as typeName, t.tmemo as typeColor, c.langue as translation, 
            (SELECT GROUP_CONCAT(CONCAT(co2.nom, \' \' ,co2.prenom) SEPARATOR \'|\') 
                FROM AppBundle\Entity\Contact co2 
                INNER JOIN AppBundle\Entity\CalL cal2 WITH co2.codco=cal2.lcal AND cal2.typlcal=:typlcal 
                INNER JOIN AppBundle\Entity\Calendrier ca2 WITH ca2.codcal=cal2.codcal 
                WHERE ca2.codcal=c.codcal) AS speakers 
             FROM AppBundle\Entity\Activite a 
            INNER JOIN AppBundle\Entity\Calendrier c WITH a.codact=c.codb 
            INNER JOIN AppBundle\Entity\Tables t WITH t.tref=a.typact 
            WHERE c.datdeb>=:start AND a.divact=:divact 
            ORDER BY c.datdeb'
        );
        $query->setParameters(['start' => new \DateTime(), 'divact' => 'RET', 'typlcal' => 'coAct']);
        return $query->getResult();
    }
}
