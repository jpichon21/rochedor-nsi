<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comprd
 *
 * @ORM\Table(name="comprd", indexes={@ORM\Index(name="CodCom", columns={"CodCom", "CodPrd"})})
 * @ORM\Entity
 */
class Comprd
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodComPrd", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcomprd;

    /**
     * @var int
     *
     * @ORM\Column(name="CodCom", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $codcom;

    /**
     * @var int
     *
     * @ORM\Column(name="CodPrd", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $codprd;

    /**
     * @var int
     *
     * @ORM\Column(name="Quant", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $quant;

    /**
     * @var string
     *
     * @ORM\Column(name="Prix", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="Remise", type="integer", nullable=false)
     */
    private $remise;

    public function __construct()
    {
        $this->remise = '';
    }

    /**
     * Get codcomprd.
     *
     * @return int
     */
    public function getCodcomprd()
    {
        return $this->codcomprd;
    }

    /**
     * Set codcom.
     *
     * @param int $codcom
     *
     * @return Comprd
     */
    public function setCodcom($codcom)
    {
        $this->codcom = $codcom;

        return $this;
    }

    /**
     * Get codcom.
     *
     * @return int
     */
    public function getCodcom()
    {
        return $this->codcom;
    }

    /**
     * Set codprd.
     *
     * @param int $codprd
     *
     * @return Comprd
     */
    public function setCodprd($codprd)
    {
        $this->codprd = $codprd;

        return $this;
    }

    /**
     * Get codprd.
     *
     * @return int
     */
    public function getCodprd()
    {
        return $this->codprd;
    }

    /**
     * Set quant.
     *
     * @param int $quant
     *
     * @return Comprd
     */
    public function setQuant($quant)
    {
        $this->quant = $quant;

        return $this;
    }

    /**
     * Get quant.
     *
     * @return int
     */
    public function getQuant()
    {
        return $this->quant;
    }

    /**
     * Set prix.
     *
     * @param string $prix
     *
     * @return Comprd
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set remise.
     *
     * @param int $remise
     *
     * @return Comprd
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise.
     *
     * @return int
     */
    public function getRemise()
    {
        return $this->remise;
    }
}
