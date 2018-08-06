<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Packaging;

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
  
    public function find($id)
    {
        $query = $this->entityManager
        ->createQuery('SELECT s
        FROM AppBundle\Entity\Shipping s 
        WHERE s.id = :id');
        $query->setParameters(['id' => $id]);
        return $query->getResult()[0];
    }


    public function findAll()
    {
        $query = $this->entityManager
        ->createQuery('SELECT s
        FROM AppBundle\Entity\Shipping s ');
        return $query->getResult();
    }


    public function findWeight($weight, $country)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p
        FROM AppBundle\Entity\Packaging p 
        WHERE :weight < p.boundary  
        ORDER BY p.boundary asc');
        $query->setParameters(['weight' => $weight]);
        $query->setMaxResults(1);
        $result = $query->getOneOrNullResult();
        if ($result === null) {
            $query = $this->entityManager
                ->createQuery('SELECT p
                FROM AppBundle\Entity\Packaging p 
                ORDER BY p.boundary DESC');
            $query->setMaxResults(1);
            $result = $query->getOneOrNullResult();
        }
        if ($country === "FR") {
            return $result->getFrance();
        } else {
            return $result->getInternational();
        }
    }

    public function findShipping($weight, $country)
    {
        
        // Try to find the matching weight's price for the requested country
        $query = $this->entityManager->createQuery(
            'SELECT s.price 
            FROM AppBundle\Entity\Shipping s 
            WHERE s.countries LIKE :country AND s.weight > :weight 
            ORDER BY s.weight'
        )
        ->setParameters(['country' => '%'.$country.'%', 'weight' => $weight])
        ->setMaxResults(1);
        $result = $query->getOneOrNullResult();
        if ($result) {
            return $result;
        }


        // Try to find the maximum weight's price for the requested country
        $query = $this->entityManager->createQuery(
            'SELECT s.price 
            FROM AppBundle\Entity\Shipping s 
            WHERE s.countries LIKE :country
            ORDER BY s.weight DESC'
        )
        ->setParameter('country', '%'.$country.'%')
        ->setMaxResults(1);
        $result = $query->getOneOrNullResult();
        if ($result) {
            return $result;
        }


        // Try to find the matching weight's price for outter (empty countries array)
        $query = $this->entityManager->createQuery(
            'SELECT s.price 
            FROM AppBundle\Entity\Shipping s 
            WHERE s.countries LIKE :country AND s.weight > :weight 
            ORDER BY s.weight'
        )
        ->setParameters(['country' => 'a:0:{}', 'weight' => $weight])
        ->setMaxResults(1);
        $result = $query->getOneOrNullResult();
        if ($result) {
            return $result;
        }

        // Try to find the matching weight's price for outter (empty array)
        $query = $this->entityManager->createQuery(
            'SELECT s.price 
            FROM AppBundle\Entity\Shipping s 
            WHERE s.countries LIKE :country
            ORDER BY s.weight DESC'
        )
        ->setParameter('country', 'a:0:{}')
        ->setMaxResults(1);
        $result = $query->getOneOrNullResult();
        if ($result) {
            return $result;
        }
    }
}
