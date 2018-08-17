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
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="decimal", precision=10, scale=2)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="countries", type="json", length=65535)
     */
    private $countries;

    public function __construct()
    {
        $this->name = '';
        $this->rate = 0;
    }


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
     * @param json $countries
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
     * @return string
     */
    public function getCountries()
    {
        return $this->countries;
    }
}
