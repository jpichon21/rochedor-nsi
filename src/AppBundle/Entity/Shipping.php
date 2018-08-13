<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shipping
 *
 * @ORM\Table(name="shipping")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShippingRepository")
 */
class Shipping
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="integer", length=255)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;


    /**
     * @var array
     *
     * @ORM\Column(name="countries", type="array")
     */
    private $countries;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Shipping
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set weight.
     *
     * @param string $weight
     *
     * @return Shipping
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Shipping
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set countries.
     *
     * @param array $countries
     *
     * @return Tax
    */
    public function setCountries($countries)
    {
        $this->countries = $countries;

        return $this;
    }

    /**
     * Get countries.
     *
     * @return array
    */
    public function getCountries()
    {
        return $this->countries;
    }
}
