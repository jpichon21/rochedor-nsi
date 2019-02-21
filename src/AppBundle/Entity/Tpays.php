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
     * @ORM\Column(name="CodPays", type="string", length=2, nullable=false)
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

    /**
     * @var integer
     *
     * @ORM\Column(name="MinLiv", type="integer", length=2,)
     */
    private $minliv;

    /**
     * @var integer
     *
     * @ORM\Column(name="MaxLiv", type="integer", length=2,)
     */
    private $maxliv;

    /**
     * @var boolean
     *
     * @ORM\Column(name="DispLiv", type="boolean")
     */
    private $displiv;

    public function __construct()
    {
        $this->nompays = '';
        $this->codpayspbx = '';
        $this->codpayspaypal = '';
        $this->codpostaux = [];
        $this->displiv = false;
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

    /**
     * Set nompays.
     *
     * @param string $nompays
     *
     * @return Tpays
     */
    public function setNompays($nompays)
    {
        $this->nompays = $nompays;

        return $this;
    }

    /**
     * Set codpayspbx.
     *
     * @param string $codpayspbx
     *
     * @return Tpays
     */
    public function setCodpayspbx($codpayspbx)
    {
        $this->codpayspbx = $codpayspbx;

        return $this;
    }

    /**
     * Get codpayspbx.
     *
     * @return string
     */
    public function getCodpayspbx()
    {
        return $this->codpayspbx;
    }

    /**
     * Set codpayspaypal.
     *
     * @param string $codpayspaypal
     *
     * @return Tpays
     */
    public function setCodpayspaypal($codpayspaypal)
    {
        $this->codpayspaypal = $codpayspaypal;

        return $this;
    }

    /**
     * Get codpayspaypal.
     *
     * @return string
     */
    public function getCodpayspaypal()
    {
        return $this->codpayspaypal;
    }

    /**
     * Set codpostaux.
     *
     * @param json $codpostaux
     *
     * @return Tpays
     */
    public function setCodpostaux($codpostaux)
    {
        $this->codpostaux = $codpostaux;

        return $this;
    }

    /**
     * Get minliv.
     *
     * @return integer
     */
    public function getMinliv()
    {
        return $this->minliv;
    }
    

    /**
     * Set minliv.
     *
     * @param integer $minliv
     *
     * @return Tpays
     */
    public function setMinliv($minliv)
    {
        $this->minliv = $minliv;

        return $this;
    }

    /**
     * Get maxliv.
     *
     * @return integer
     */
    public function getMaxliv()
    {
        return $this->maxliv;
    }

    /**
     * Set maxliv.
     *
     * @param integer $maxliv
     *
     * @return Tpays
     */
    public function setMaxliv($maxliv)
    {
        $this->maxliv = $maxliv;

        return $this;
    }

    
    /**
     * Get displiv.
     *
     * @return integer
     */
    public function getDispliv()
    {
        return $this->displiv;
    }

    /**
     * Set displiv.
     *
     * @param integer $displiv
     *
     * @return Tpays
     */
    public function setDispliv($displiv)
    {
        $this->displiv = $displiv;
        
        return $this;
    }
}
