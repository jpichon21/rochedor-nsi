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
     * @ORM\Column(name="zipcodes", type="array")
    */
    private $zipcodes;

    public function getNompays()
    {
        return $this->nompays;
    }

    public function getCodpays()
    {
        return $this->codpays;
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
}
