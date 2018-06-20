<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Produit;

class ProductRepository
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
    public function findProduct($productId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codprd=:productId');
        $query->setParameter('productId', $productId);
        return $query->getOneOrNullResult();
    }

    /**
    * Find Collection of Produit by Prodrub
    *
    * @param int Prodrub ud
    * @return Array
    */
    public function findProducts($rubId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codrub=:rubId');
        $query->setParameter('rubId', $rubId);
        return $query->getResult();
    }

    /**
    * Find new products
    *
    * @return Array
    */
    public function findNewProducts()
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.nouveaute=true ORDER BY p.maj DESC');
        $query->setMaxResults(4);
        return $query->getResult();
    }
}
