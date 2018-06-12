<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Base;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CalendarRepository
{
    
    /**
     * @var EntityRepository
     */
    private $repository;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Base::class);
    }


    public function findSites()
    {
        $query = $this->repository->createQueryBuilder('b')
        ->join('AppBundle:BaseL', 'bl', 'WITH', 'b.cle = bl.cle')
        ->where('bl.clp=111');
        return $query->getQuery()->getResult();
    }
}
