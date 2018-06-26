<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Produit;

/**
 * CartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartRepository
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
    * Find Cart by its Id
    *
    * @param int $cartId
    * @return Cart
    */
    public function find($cartId)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\Cart c WHERE c.id=:cartId');
        $query->setParameter('cartId', $cartId);
        return $query->getOneOrNullResult();
    }

    /**
    * Find CartLine
    *
    * @param Produit $product
    * @param Cart $cart
    * @return CartLine
    */
    public function findCartline($cart, $product)
    {
        $query = $this->entityManager
        ->createQuery('SELECT c FROM AppBundle\Entity\CartLine c WHERE c.product=:product AND c.cart=:cart');
        $query->setParameters(['cart' => $cart, 'product' => $product]);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }
}
