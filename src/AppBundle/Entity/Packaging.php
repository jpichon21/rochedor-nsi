<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Packaging
 *
 * @ORM\Table(name="packaging")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackagingRepository")
 */
class Packaging
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPack", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Limitation", type="integer", length=11)
     */
    private $limit;

    /**
     * @var int
     *
     * @ORM\Column(name="Europe", type="integer", length=11)
     */
    private $europe;

    /**
     * @var int
     *
     * @ORM\Column(name="International", type="integer", length=11)
     */
    private $international;

    public function __construct()
    {
        $this->limit = 0;
        $this->europe = 0;
        $this->international = 0;
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
     * Set limit.
     *
     * @param int $limit
     *
     * @return Packaging
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get limit.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set Europe.
     *
     * @param int $europe
     *
     * @return Packaging
     */
    public function setEurope($europe)
    {
        $this->europe = $europe;

        return $this;
    }

    /**
     * Get Europe.
     *
     * @return int
     */
    public function getEurope()
    {
        return $this->europe;
    }

    /**
     * Set international.
     *
     * @param int $international
     *
     * @return Packaging
     */
    public function setInternational($international)
    {
        $this->international = $international;

        return $this;
    }

    /**
     * Get international.
     *
     * @return int
     */
    public function getInternational()
    {
        return $this->international;
    }
}
