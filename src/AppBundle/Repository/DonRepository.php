<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * DonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DonRepository
{

    /**
    * @var EntityManagerInterface
    */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
    * Find last year's ref
    *
    */
    public function findLastRef($year)
    {
        $query = $this->entityManager
        ->createQuery('SELECT d.refdon
        FROM AppBundle\Entity\Don d 
        WHERE d.refdon LIKE :year
        ORDER BY d.refdon DESC');
        $query->setParameter('year', $year.'-%')
        ->setMaxResults(1);
        $lastRef = $query->getOneOrNullResult();
        if (is_array($lastRef) && array_key_exists('refdon', $lastRef)) {
            return $lastRef['refdon'];
        }

        return null;
    }

    /**
     * Find by reference
     */
    public function findByRef($ref)
    {
        $query = $this->entityManager
        ->createQuery('SELECT d
        FROM AppBundle\Entity\Don d 
        WHERE d.refdon LIKE :ref');
        $query->setParameter('ref', $ref)
        ->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    public function isFirstGiftOfYear($codCo)
    {
        $yearBegin = new \DateTime();
        $yearBegin->modify('-1 year');

        $year = new \DateTime();

        $dateBegin = new \DateTime();
        $dateBegin->setDate($yearBegin->format('Y'), 10, 01);
        $dateBegin->setTime(0, 0, 0);

        $dateEnd = new \DateTime();
        $dateEnd->setDate($year->format('Y'), 9, 30);
        $dateEnd->setTime(23, 59, 59);

        $query = $this->entityManager->createQueryBuilder('d');
        $query->select('d')
            ->from('AppBundle:Don', 'd')
            ->andWhere('d.contact = :codCo')
            ->andWhere('d.enregdon >= :dateBegin')
            ->andWhere('d.enregdon <= :dateEnd')
            ->andWhere('d.status = :status');
        $query->setParameter('codCo', $codCo)
            ->setParameter('dateBegin', $dateBegin)
            ->setParameter('dateEnd', $dateEnd)
            ->setParameter('status', 'success');

        $result = $query->getQuery()->getResult();

        return empty($result);
    }
}
