<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Prodrub;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Produit;
use Doctrine\ORM\Query\Expr\Join;
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
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codrub IN (:rubId)');
        $query->setParameter('rubId', $rubId);
        $query->setMaxResults($limit);
        return $query->getResult();
    }

    /**
    * Find Collection of Produit by locale
    *
    * @param string $locale
    * @return Array
    */
    public function findCollectionsByLocale($locale, $limit = 99)
    {

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('DISTINCT(p.codrub)')
            ->from('AppBundle:Prodrub', 'p')
            ->andWhere('p.langrub = :locale')
            ->setParameter('locale', $locale);

dump($qb->getQuery()->getSQL());
dump($qb->getQuery()->getParameters());
dump($qb->getQuery()->getResult());

        return $qb->getQuery()->getResult();
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
        WHERE r.rubhide=0 ORDER BY LOCATE(:locale, r.langrub) DESC, r.rubrique')
        ->setParameter('locale', $locale);
        return $query->getResult();
    }

    /**
    * Find locales
    *
    * @return array
    */
    public function findLocales()
    {
        $query = $this->entityManager
        ->createQuery('SELECT DISTINCT(r.langrub) as langrub FROM AppBundle\Entity\Prodrub r
        WHERE r.rubhide=0 ORDER BY r.langrub');

        return array_column($query->getResult(), 'langrub');
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
    * @return Produit
    */
    public function find($productId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT p FROM AppBundle\Entity\Produit p WHERE p.codprd=:productId');
        $query->setParameter('productId', $productId);
        return $query->getResult()[0];
    }
}
