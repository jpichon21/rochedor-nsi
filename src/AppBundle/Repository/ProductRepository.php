<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Prodrub;
use Doctrine\ORM\AbstractQuery;
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
    * @return array
    */
    public function findProduct($productId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p, r 
        FROM AppBundle\Entity\Produit p 
        JOIN AppBundle\Entity\Prodrub r 
        WITH r.codrub=p.codrub 
        WHERE p.codprd=:productId');
        $query->setParameter('productId', $productId);
        return $query->getArrayResult();
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
    * Find Collection of Produit by theme filters
    *
    * @param string $support
    * @param string $author
    * @param string $gender
    * @param string $theme
     *
    * @return array
    */
    public function findByThemesFilter($support, $author, $gender, $theme)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle:Produit', 'p');
        if (!empty($support)) {
            $qb->andWhere('p.typprd = :support')->setParameter('support', $support);
        }
        if (!empty($author)) {
            $qb->andWhere('p.auteur = :author')->setParameter('author', $author);
        }
        if (!empty($gender)) {
            $qb->andWhere('p.genre = :gender')->setParameter('gender', $gender);
        }
        if (!empty($theme)) {
            $qb->andWhere('REGEXP(p.themes, :theme) = 1')->setParameter('theme', $theme);
        }

        return $qb->getQuery()->getResult();
    }

    /**
    * Find new products
    *
    * @return array
    */
    public function findNewProducts()
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.nouveaute=true ORDER BY p.rang ASC');
        $query->setMaxResults(5);
        return $query->getArrayResult();
    }

    /**
    * Find collections
    *
    * @return array
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
     * @return array
     */
    public function findSupports()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p.typprd')
            ->from('AppBundle:Produit', 'p')
            ->leftJoin('AppBundle:ProdRub', 'prub', 'WITH', 'prub.codrub = p.codrub')
            ->orderBy('p.typprd')
            ->distinct()
        ;

        return array_filter(array_column($qb->getQuery()->getScalarResult(), 'typprd'));
    }

    /**
     * @return array
     */
    public function findAuthors()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p.auteur')
            ->from('AppBundle:Produit', 'p')
            ->orderBy('p.auteur')
            ->distinct()
        ;

        return array_filter(array_column($qb->getQuery()->getScalarResult(), 'auteur'));
    }

    /**
     * @return array
     */
    public function findGenders()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p.genre')
            ->from('AppBundle:Produit', 'p')
            ->orderBy('p.genre')
            ->distinct()
        ;

        return array_filter(array_column($qb->getQuery()->getScalarResult(), 'genre'));
    }

    /**
     * @throws \Exception
     *
     * @return array
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
        $themes = $query->fetchAll();

        $themes = array_filter($themes, function ($theme) {
            return $theme['theme'] != '';
        });

        return array_column($themes, 'theme');
    }

    /**
    * Find themes
    *
    * @return Product
    */
    public function find($productId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codprd=:productId');
        $query->setParameter('productId', $productId);
        return $query->getResult()[0];
    }


    /**
     * Find applicable tax for a given product and country
     *
     * @param integer $productId
     * @param string $country
     * @return AppBundle\Entity\Tax|null
     */
    public function findTax($productId, $country)
    {
        $product = $this->find($productId);
        $taxes = $product->getTaxes();
        foreach ($taxes as $tax) {
            if (in_array($country, $tax->getCountries())) {
                return $tax;
            }
        }
        return null;
    }
}
