<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalL
 *
 * @ORM\Table(
 *  name="cal_l",
 *  indexes={
 *  @ORM\Index(name="CodCo", * columns={"LCal"}),
 *  @ORM\Index(name="CodCal", columns={"CodCal"}), @ORM\Index(name="hebLCal", columns={"hebLCal"})
 *  }
 * )
 * @ORM\Entity
 */
class CalL
{

    public const TYP_LCAL_PARTICIPANT = 'coIns';
    public const TYP_LCAL_INTERVENANT = 'coAct';

    /**
     * @var int
     *
     * @ORM\Column(name="CodCalL", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcall;

    /**
     * @var int
     *
     * @ORM\Column(name="CodCal", type="integer", nullable=false)
     */
    private $codcal;

    /**
     * @var int
     *
     * @ORM\Column(name="LCal", type="integer", nullable=false)
     */
    private $lcal;

    /**
     * @var string
     *
     * @ORM\Column(name="TypLCal", type="string", length=6, nullable=false)
     */
    private $typlcal;

    /**
     * @var string
     *
     * @ORM\Column(name="RefLCal", type="string", length=20, nullable=false)
     */
    private $reflcal;

    /**
     * @var string
     *
     * @ORM\Column(name="EtapLCal", type="string", length=6, nullable=false)
     */
    private $etaplcal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RepLCal", type="date", nullable=false)
     */
    private $replcal;

    /**
     * @var int
     *
     * @ORM\Column(name="HebLCal", type="integer", nullable=false)
     */
    private $heblcal;

    /**
     * @var string
     *
     * @ORM\Column(name="ChLCal", type="string", length=6, nullable=false)
     */
    private $chlcal;

    /**
     * @var bool
     *
     * @ORM\Column(name="SaisieLCal", type="boolean", nullable=false)
     */
    private $saisielcal;

    /**
     * @var string
     *
     * @ORM\Column(name="CreatLCal", type="string", length=15, nullable=false)
     */
    private $creatlcal;

    /**
     * @var int
     *
     * @ORM\Column(name="TriLCal", type="smallint", nullable=false)
     */
    private $trilcal;

    /**
     * @var bool
     *
     * @ORM\Column(name="SelLCal", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sellcal = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="JSLCal", type="text", length=65535, nullable=false)
     */
    private $jslcal;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoLCal", type="text", length=65535, nullable=false)
     */
    private $memolcal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EnregLCal", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $enreglcal = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="OldIns", type="integer", nullable=false)
     */
    private $oldins;

    public function __construct()
    {
        $this->setEnreglcal(new \DateTime())
        ->setEtaplcal('')
        ->setReplcal(new \DateTime('0000-00-00 00:00:00'))
        ->setHeblcal(0)
        ->setChlcal('')
        ->setSaisielcal(1)
        ->setCreatlcal('')
        ->setTrilcal(0)
        ->setJslcal('')
        ->setMemolcal('')
        ->setOldIns(0);
    }

    /**
     * Get codcall.
     *
     * @return int
     */
    public function getCodcall()
    {
        return $this->codcall;
    }

    /**
     * Set codcal.
     *
     * @param int $codcal
     *
     * @return CalL
     */
    public function setCodcal($codcal)
    {
        $this->codcal = $codcal;

        return $this;
    }

    /**
     * Get codcal.
     *
     * @return int
     */
    public function getCodcal()
    {
        return $this->codcal;
    }

    /**
     * Set lcal.
     *
     * @param int $lcal
     *
     * @return CalL
     */
    public function setLcal($lcal)
    {
        $this->lcal = $lcal;

        return $this;
    }

    /**
     * Get lcal.
     *
     * @return int
     */
    public function getLcal()
    {
        return $this->lcal;
    }

    /**
     * Set typlcal.
     *
     * @param string $typlcal
     *
     * @return CalL
     */
    public function setTyplcal($typlcal)
    {
        $this->typlcal = $typlcal;

        return $this;
    }

    /**
     * Get typlcal.
     *
     * @return string
     */
    public function getTyplcal()
    {
        return $this->typlcal;
    }

    /**
     * Set reflcal.
     *
     * @param string $reflcal
     *
     * @return CalL
     */
    public function setReflcal($reflcal)
    {
        $this->reflcal = $reflcal;

        return $this;
    }

    /**
     * Get reflcal.
     *
     * @return string
     */
    public function getReflcal()
    {
        return $this->reflcal;
    }

    /**
     * Set etaplcal.
     *
     * @param string $etaplcal
     *
     * @return CalL
     */
    public function setEtaplcal($etaplcal)
    {
        $this->etaplcal = $etaplcal;

        return $this;
    }

    /**
     * Get etaplcal.
     *
     * @return string
     */
    public function getEtaplcal()
    {
        return $this->etaplcal;
    }

    /**
     * Set replcal.
     *
     * @param \DateTime $replcal
     *
     * @return CalL
     */
    public function setReplcal($replcal)
    {
        $this->replcal = $replcal;

        return $this;
    }

    /**
     * Get replcal.
     *
     * @return \DateTime
     */
    public function getReplcal()
    {
        return $this->replcal;
    }

    /**
     * Set heblcal.
     *
     * @param int $heblcal
     *
     * @return CalL
     */
    public function setHeblcal($heblcal)
    {
        $this->heblcal = $heblcal;

        return $this;
    }

    /**
     * Get heblcal.
     *
     * @return int
     */
    public function getHeblcal()
    {
        return $this->heblcal;
    }

    /**
     * Set chlcal.
     *
     * @param string $chlcal
     *
     * @return CalL
     */
    public function setChlcal($chlcal)
    {
        $this->chlcal = $chlcal;

        return $this;
    }

    /**
     * Get chlcal.
     *
     * @return string
     */
    public function getChlcal()
    {
        return $this->chlcal;
    }

    /**
     * Set saisielcal.
     *
     * @param bool $saisielcal
     *
     * @return CalL
     */
    public function setSaisielcal($saisielcal)
    {
        $this->saisielcal = $saisielcal;

        return $this;
    }

    /**
     * Get saisielcal.
     *
     * @return bool
     */
    public function getSaisielcal()
    {
        return $this->saisielcal;
    }

    /**
     * Set creatlcal.
     *
     * @param string $creatlcal
     *
     * @return CalL
     */
    public function setCreatlcal($creatlcal)
    {
        $this->creatlcal = $creatlcal;

        return $this;
    }

    /**
     * Get creatlcal.
     *
     * @return string
     */
    public function getCreatlcal()
    {
        return $this->creatlcal;
    }

    /**
     * Set trilcal.
     *
     * @param int $trilcal
     *
     * @return CalL
     */
    public function setTrilcal($trilcal)
    {
        $this->trilcal = $trilcal;

        return $this;
    }

    /**
     * Get trilcal.
     *
     * @return int
     */
    public function getTrilcal()
    {
        return $this->trilcal;
    }

    /**
     * Set sellcal.
     *
     * @param bool $sellcal
     *
     * @return CalL
     */
    public function setSellcal($sellcal)
    {
        $this->sellcal = $sellcal;

        return $this;
    }

    /**
     * Get sellcal.
     *
     * @return bool
     */
    public function getSellcal()
    {
        return $this->sellcal;
    }

    /**
     * Set jslcal.
     *
     * @param string $jslcal
     *
     * @return CalL
     */
    public function setJslcal($jslcal)
    {
        $this->jslcal = $jslcal;

        return $this;
    }

    /**
     * Get jslcal.
     *
     * @return string
     */
    public function getJslcal()
    {
        return $this->jslcal;
    }

    /**
     * Set memolcal.
     *
     * @param string $memolcal
     *
     * @return CalL
     */
    public function setMemolcal($memolcal)
    {
        $this->memolcal = $memolcal;

        return $this;
    }

    /**
     * Get memolcal.
     *
     * @return string
     */
    public function getMemolcal()
    {
        return $this->memolcal;
    }

    /**
     * Set enreglcal.
     *
     * @param \DateTime $enreglcal
     *
     * @return CalL
     */
    public function setEnreglcal($enreglcal)
    {
        $this->enreglcal = $enreglcal;

        return $this;
    }

    /**
     * Get enreglcal.
     *
     * @return \DateTime
     */
    public function getEnreglcal()
    {
        return $this->enreglcal;
    }

    /**
     * Set oldins.
     *
     * @param int $oldins
     *
     * @return CalL
     */
    public function setOldins($oldins)
    {
        $this->oldins = $oldins;

        return $this;
    }

    /**
     * Get oldins.
     *
     * @return int
     */
    public function getOldins()
    {
        return $this->oldins;
    }
}
