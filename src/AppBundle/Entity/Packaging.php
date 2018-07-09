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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="boundary", type="integer" , nullable=true)
     */
    private $boundary;

    /**
     * @var int
     *
     * @ORM\Column(name="france", type="integer")
     */
    private $france;

    /**
     * @var int
     *
     * @ORM\Column(name="international", type="integer")
     */
    private $international;

    /**
     * @var int
     *
     * @ORM\Column(name="maximal_boundary", type="boolean")
     */
    private $maximalBoundary;


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
     * Set boundary.
     *
     * @param int $boundary
     *
     * @return Packaging
     */
    public function setBoundary($boundary)
    {
        $this->boundary = $boundary;

        return $this;
    }

    /**
     * Get boundary.
     *
     * @return int
     */
    public function getBoundary()
    {
        return $this->boundary;
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

        /**
     * Set maximalBoundary.
     *
     * @param int $maximalBoundary
     *
     * @return Packaging
     */
    public function setMaximalBoundary($maximalBoundary)
    {
        $this->maximalBoundary = $maximalBoundary;

        return $this;
    }

    /**
     * Get maximalBoundary.
     *
     * @return int
     */
    public function getMaximalBoundary()
    {
        return $this->maximalBoundary;
    }
}
