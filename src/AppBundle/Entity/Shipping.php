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
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;


    /**
     * @var array
     *
     * @ORM\Column(name="relatedcountrys", type="array")
     */
    private $relatedcountrys;

    /**
    * @var int
    *
    * @ORM\Column(name="maximal_weight", type="boolean")
     */
    private $maximalWeight;

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
     * Set country.
     *
     * @param string $country
     *
     * @return Shipping
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
     * Set relatedcountrys.
     *
     * @param array $relatedcountrys
     *
     * @return Tax
    */
    public function setRelatedcountrys($relatedcountrys)
    {
        $this->relatedcountrys = $relatedcountrys;

        return $this;
    }

    /**
     * Get relatedcountrys.
     *
     * @return array
    */
    public function getRelatedcountrys()
    {
        return $this->relatedcountrys;
    }

            /**
     * Set maximalWeight.
     *
     * @param int $maximalWeight
     *
     * @return Packaging
     */
    public function setMaximalWeight($maximalWeight)
    {
        $this->maximalWeight = $maximalWeight;

        return $this;
    }

    /**
     * Get maximalWeight.
     *
     * @return int
     */
    public function getMaximalWeight()
    {
        return $this->maximalWeight;
    }
}
