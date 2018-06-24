<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Produit;
use Doctrine\ORM\Query\ResultSetMapping;

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
    * @param int $rubId
    * @return Array
    */
    public function findProducts($rubId, $limit = 99)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codrub=:rubId');
        $query->setParameter('rubId', $rubId);
        $query->setMaxResults($limit);
        return $query->getResult();
    }

    /**
    * Find Collection of Produit by theme
    *
    * @param array $themes
    * @return Array
    */
    public function findByThemes($themes)
    {
        $query = $this->entityManager
        ->createQuery("SELECT p FROM AppBundle\Entity\Produit p WHERE REGEXP(p.themes, :themes) = 1");
        $query->setParameter('themes', $themes);
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

    /**
    * Find collections
    *
    * @return Array
    */
    public function findCollections($locale)
    {
        $query = $this->entityManager
        ->createQuery('SELECT r FROM AppBundle\Entity\Prodrub r
        WHERE r.rubhide=0 AND r.langrub=:locale ORDER BY r.rubrique ASC');
        $query->setParameter('locale', strtoupper($locale));
        return $query->getResult();
    }

    /**
    * Find themes
    *
    * @return Array
    */
    public function findThemes()
    {
        $query = $this->entityManager->getConnection()->prepare("SELECT DISTINCT      
        SUBSTRING_INDEX(SUBSTRING_INDEX(p.themes, ',', t.n), ' ', -1) theme
        FROM (SELECT 1 n UNION ALL SELECT 2
        UNION ALL SELECT 3 UNION ALL SELECT 4
        ) t INNER JOIN produit p
        ON CHAR_LENGTH(p.themes)-CHAR_LENGTH(REPLACE(p.themes, ' ', ''))>=t.n-1
        WHERE themes <> ''
        ORDER BY
        theme");
        $query->execute();
        return $query->fetchAll();
    }
}
