<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tax
 *
 * @ORM\Table(name="tax")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxRepository")
 */
class Tax
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
     * @var float
     *
     * @ORM\Column(name="rate", type="float")
     */
    private $rate;

    /**
     * @var array
     *
     * @ORM\Column(name="countries", type="array")
     */
    private $countries;

    /**
     * @var array
     *
     * @ORM\Column(name="zipcodes", type="array")
     */
    private $zipcodes;

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
     * @return Tax
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
     * Set rate.
     *
     * @param float $rate
     *
     * @return Tax
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
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

    /**
     * Set zipcodes.
     *
     * @param array $zipcodes
     *
     * @return Tax
     */
    public function setZipcode($zipcodes)
    {
        $this->zipcodes = $zipcodes;

        return $this;
    }

    /**
     * Get zipcodes.
     *
     * @return array
     */
    public function getZipcodes()
    {
        return $this->zipcodes;
    }
        
    /**
     * Set zipcodes.
     *
     * @param array $zipcodes
     *
     * @return Tax
     */
    public function setZipcodes($zipcodes)
    {
        $this->zipcodes = $zipcodes;

        return $this;
    }
}
