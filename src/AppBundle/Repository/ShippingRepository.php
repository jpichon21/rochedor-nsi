<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * ShippingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShippingRepository
{
    const SHIPPINGWEIGHT = 40;
    
    /**
    * @var EntityManagerInterface
    */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findGoodPort($weight, $country, $name)
    {
        
        $weight = $weight + $this::SHIPPINGWEIGHT;
        dump($weight);
        if ($country != 'FR') {
            $country = 'HF';
        }
        
        if ($name === "Font" || $name === "Roche") {
            return $port = 0;
        }
        
        $query = $this->entityManager
        ->createQuery('SELECT s.price
        FROM AppBundle\Entity\Shipping s 
        WHERE s.country=:country
        AND :weight < s.weight
        ORDER BY s.price asc');
        $query->setParameters(['country' => $country, 'weight' => $weight]);
        $query->setMaxResults(1);
         
        return $query->getResult()[0]['price'];
    }
}
