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
     * @ORM\Column(name="IdPack", type="integer")
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
     * @ORM\Column(name="France", type="integer", length=11)
     */
    private $france;

    /**
     * @var int
     *
     * @ORM\Column(name="International", type="integer", length=11)
     */
    private $international;

    public function __construct()
    {
        $this->limit = 0;
        $this->france = 0;
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
     * Set france.
     *
     * @param int $france
     *
     * @return Packaging
     */
    public function setFrance($france)
    {
        $this->france = $france;

        return $this;
    }

    /**
     * Get france.
     *
     * @return int
     */
    public function getFrance()
    {
        return $this->france;
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
