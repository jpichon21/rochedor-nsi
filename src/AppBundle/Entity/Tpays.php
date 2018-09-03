<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tpays
 *
 * @ORM\Table(name="tpays")
 * @ORM\Entity
 */
class Tpays
{
    /**
     * @var string
     *
     * @ORM\Column(name="NomPays", type="string", length=30, nullable=false)
     */
    private $nompays;

    /**
     * @var string
     *
     * @ORM\Column(name="CodPays", type="string", length=2)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codpays;

    /**
     * @var string
     *
     * @ORM\Column(name="CodPaysPBX", type="string", length=3)
     */
    private $codpayspbx;

    /**
     * @var string
     *
     * @ORM\Column(name="CodPaysPaypal", type="string", length=5)
     */
    private $codpayspaypal;

    /**
     * @var array
     *
     * @ORM\Column(name="CodPostaux", type="json", length=65535)
    */
    private $codpostaux;

    public function __construct()
    {
        $this->nompays = '';
        $this->codpays = '';
        $this->codpayspbx = '';
        $this->codpayspaypal = '';
        $this->codpostaux = [];
    }

    public function getNompays()
    {
        return $this->nompays;
    }

    public function getCodpays()
    {
        return $this->codpays;
    }

    /**
     * Set codpostaux.
     *
     * @param array $codpostaux
     *
     * @return Tax
    */
    public function setZipcode($codpostaux)
    {
        $this->codpostaux = $codpostaux;

        return $this;
    }

    /**
     * Get codpostaux.
     *
     * @return array
    */
    public function getCodpostaux()
    {
        return $this->codpostaux;
    }
}
