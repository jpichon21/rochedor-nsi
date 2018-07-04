<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Produit;
use Doctrine\ORM\Query\ResultSetMapping;

class TaxRepository
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
        $this->entityManager = $entityManager;
    }
    
    /**
    * Find Produit by its Id
    *
    * @param int productId
    * @return Produit
    */
    public function findTax($productId, $country)
    {
        $query = $this->entityManager
        ->createQuery('SELECT t.name, t.rate, t.countries 
        FROM AppBundle\Entity\Produit p
        JOIN produits_taxes pt
        WITH p.codprd = pt.produit_id
        JOIN AppBundle\Entity\Tax t
        WITH pt.tax_id = t.id
        WHERE p.codprd=:productId');

        $qb = $this->entityManager->createQueryBuilder()
        ->select('p.codprd', 't.name')
        ->from('AppBundle\Entity\Produit', 'p')
        ->join('AppBundle\Entity\Tax', 't')
        ->where('p.codprd = :productId')
        ->setParameter('productId', $productId);

        $results = $qb->getQuery()->getResult();
// dump($results);
        return $results;
        // $query->setParameter('productId', $productId);
        // $results =  $query->getResult();

        // foreach ($results as $k => $result) {
        //     if (in_array($country, $result['countries'])) {
        //         return $result;
        //     }
        // }
    }
}
