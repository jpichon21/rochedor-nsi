<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cartline
 *
 * @ORM\Table(name="ligne_panier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartlineRepository")
 */
class Cartline
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLPanier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Qte", type="integer")
     */
    private $quantity;

    /**
     * @var AppBundle\Entity\Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="IdProd", referencedColumnName="CodPrd")
    */
    private $product;

    /**
     * @var \AppBundle\Entity\Cart
     *
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="cartlines")
     * @ORM\JoinColumn(name="panier", referencedColumnName="idPanier")
    */
    private $cart;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Cartline
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Produit|null $product
     *
     * @return Cartline
     */
    public function setProduct(\AppBundle\Entity\Produit $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Produit|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set cart.
     *
     * @param \AppBundle\Entity\Cart|null $cart
     *
     * @return Cartline
     */
    public function setCart(\AppBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart.
     *
     * @return \AppBundle\Entity\Cart|null
     */
    public function getCart()
    {
        return $this->cart;
    }
}
