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
        
        if ($query->getOneOrNullResult() === null) {
            $query2 = $this->entityManager
            ->createQuery('SELECT p
            FROM AppBundle\Entity\Packaging p 
            WHERE p.maximalBoundary = 1');
            $query2->setMaxResults(1);
            if ($country === "FR") {
                return $query2->getResult()[0]->getFrance();
            } else {
                return $query2->getResult()[0]->getInternational();
            }
        }

        if ($country === "FR") {
            return $query->getResult()[0]->getFrance();
        } else {
            return $query->getResult()[0]->getInternational();
        }
        exit;
    }

    public function findShipping($weight, $country, $name)
    {

        $supplementWeight =  $this->findWeight($weight, $country);
        $weight = $weight + $supplementWeight;
        $result['suplementWeight'] = $supplementWeight;

        if ($name === "Font" || $name === "Roche") {
            return ['suplementWeight' => 0, 'price' => 0];
        }

        $maximalWeight = 0;
        $queryWeight = $this->entityManager
        ->createQuery('SELECT MAX(s.weight)
        FROM AppBundle\Entity\Shipping s 
        WHERE s.relatedcountries LIKE :country ');
        $queryWeight->setParameters(['country' => '%'.$country.'%']);
        if ($queryWeight->getResult()[0][1] != null) {
            $maximalWeight = $queryWeight->getResult()[0][1];
        } else {
            $countryWeight = "HF";
            $queryWeight2 = $this->entityManager
            ->createQuery('SELECT MAX(s.weight)
            FROM AppBundle\Entity\Shipping s 
            WHERE s.country = :countryWeight');
            $queryWeight2->setParameters(['countryWeight' => $countryWeight]);
            $maximalWeight = $queryWeight2->getResult()[0][1];
        }


        $query = $this->entityManager
        ->createQuery('SELECT s.price
        FROM AppBundle\Entity\Shipping s 
        WHERE s.relatedcountries LIKE :country
        AND :weight < s.weight
        ORDER BY s.price asc');
        $query->setParameters(['country' => '%'.$country.'%', 'weight' => $weight]);
        $query->setMaxResults(1);
        
        if ($query->getOneOrNullResult() != null) {
            $result ['price'] = $query->getOneOrNullResult()['price'];
            return $result;
        } elseif ($weight > $maximalWeight) {
            $query = $this->entityManager
            ->createQuery('SELECT s.price
            FROM AppBundle\Entity\Shipping s 
            WHERE s.relatedcountries LIKE :country
            AND s.maximalWeight = 1');
            $query->setParameters(['country' => '%'.$country.'%']);
            $query->setMaxResults(1);
            
            if ($query->getOneOrNullResult() != null) {
                $result ['price'] = $query->getOneOrNullResult()['price'];
                return $result;
            } else {
                $country ="HF";
                $query = $this->entityManager
                ->createQuery('SELECT s.price
                FROM AppBundle\Entity\Shipping s 
                WHERE s.country LIKE :country
                AND s.maximalWeight = 1');
                $query->setParameters(['country' => $country]);
                $result ['price'] = $query->getOneOrNullResult()['price'];
                return $result;
            }
        } else {
            $country = "HF";
            $query = $this->entityManager
            ->createQuery('SELECT s.price
            FROM AppBundle\Entity\Shipping s 
            WHERE s.country = :country
            AND :weight < s.weight
            ORDER BY s.price asc');
            $query->setParameters(['country' => $country, 'weight' => $weight]);
            $query->setMaxResults(1);
            $result ['price'] = $query->getOneOrNullResult()['price'];
            return $result;
        }
    }
}
