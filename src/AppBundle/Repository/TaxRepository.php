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
        FROM AppBundle\Entity\Tax t 
        JOIN AppBundle\Entity\Produit p
        WHERE p.codprd=:productId');
        $query->setParameter('productId', $productId);
        $results =  $query->getResult();

        foreach ($results as $k => $result) {
            if (in_array($country, $result['countries'])) {
                return $result;
            }
        }
    }
}
