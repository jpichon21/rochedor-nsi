<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Don
 *
 * @ORM\Table(
 * name="don",
 * indexes={@ORM\Index(name="DonCo", columns={"DonCo"}),
 * @ORM\Index(name="BanqDon", columns={"BanqDon"})})
 * @ORM\Entity
 */
class Don
{
    /**
     * @var string
     *
     * @ORM\Column(name="RefDon", type="string", length=9, nullable=true)
     */
    private $refdon;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="dons")
     * @ORM\JoinColumn(name="DonCo", referencedColumnName="CodCo")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="MntDon", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mntdon;

    /**
     * @var string
     *
     * @ORM\Column(name="MonDon", type="string", length=2, nullable=true)
     */
    private $mondon;

    /**
     * @var string
     *
     * @ORM\Column(name="DestDon", type="string", length=6, nullable=true)
     */
    private $destdon;

    /**
     * @var integer
     *
     * @ORM\Column(name="BanqDon", type="integer", nullable=true)
     */
    private $banqdon;

    /**
     * @var string
     *
     * @ORM\Column(name="ModDon", type="string", length=6, nullable=true)
     */
    private $moddon;

    /**
     * @var integer
     *
     * @ORM\Column(name="NoDonR", type="integer", nullable=true)
     */
    private $nodonr;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ValidDon", type="boolean", nullable=true)
     */
    private $validdon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="NoRecu", type="boolean", nullable=true)
     */
    private $norecu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Adhesion", type="boolean", nullable=true)
     */
    private $adhesion;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoDon", type="text", length=65535, nullable=true)
     */
    private $memodon;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatDon", type="datetime", nullable=true)
     */
    private $datdon;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatRecu", type="date", nullable=true)
     */
    private $datrecu;

    /**
     * @var string
     *
     * @ORM\Column(name="TransDon", type="string", length=20, nullable=true)
     */
    private $transdon;

    /**
     * @var string
     *
     * @ORM\Column(name="PaysDon", type="string", length=4, nullable=true)
     */
    private $paysdon;

    /**
     * @var string
     *
     * @ORM\Column(name="MsgBanq", type="string", length=20, nullable=true)
     */
    private $msgbanq;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EnregDon", type="datetime", nullable=true)
     */
    private $enregdon;

    /**
     * @var string
     *
     * @ORM\Column(name="CreatDon", type="string", length=15, nullable=true)
     */
    private $creatdon;

    /**
     * @var integer
     *
     * @ORM\Column(name="OldDon", type="integer", nullable=true)
     */
    private $olddon;

    /**
     * @var integer
     *
     * @ORM\Column(name="CodDon", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $coddon;

    /**
     * @var string
     *
     * @ORM\Column(name="Status", type="string", length=255, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->enregdon = new \DateTime();
        $this->refdon = '';
        $this->mntdon = 0;
        $this->mondon = '';
        $this->destdon = '';
        $this->banqdon = 0;
        $this->moddon = '';
        $this->nodonr = 0;
        $this->validdon = 0;
        $this->norecu = 0;
        $this->adhesion = 0;
        $this->memodon = '';
        $this->datdon = new \DateTime('0000-00-00 00:00:00');
        $this->datrecu = new \DateTime('0000-00-00 00:00:00');
        $this->transdon = '';
        $this->paysdon = '';
        $this->msgbanq = '';
        $this->creatdon = '';
        $this->olddon = 0;
        $this->status = '';
    }

    /**
     * Set refdon
     *
     * @param string $refdon
     *
     * @return Don
     */
    public function setRefdon($refdon)
    {
        $this->refdon = $refdon;

        return $this;
    }

    /**
     * Get refdon
     *
     * @return string
     */
    public function getRefdon()
    {
        return $this->refdon;
    }

      /**
     * Set mntdon
     *
     * @param string $mntdon
     *
     * @return Don
     */
    public function setMntdon($mntdon)
    {
        $this->mntdon = $mntdon;

        return $this;
    }

    /**
     * Get mntdon
     *
     * @return string
     */
    public function getMntdon()
    {
        return $this->mntdon;
    }

    /**
     * Set mondon
     *
     * @param string $mondon
     *
     * @return Don
     */
    public function setMondon($mondon)
    {
        $this->mondon = $mondon;

        return $this;
    }

    /**
     * Get mondon
     *
     * @return string
     */
    public function getMondon()
    {
        return $this->mondon;
    }

    /**
     * Set destdon
     *
     * @param string $destdon
     *
     * @return Don
     */
    public function setDestdon($destdon)
    {
        $this->destdon = $destdon;

        return $this;
    }

    /**
     * Get destdon
     *
     * @return string
     */
    public function getDestdon()
    {
        return $this->destdon;
    }

    /**
     * Set banqdon
     *
     * @param integer $banqdon
     *
     * @return Don
     */
    public function setBanqdon($banqdon)
    {
        $this->banqdon = $banqdon;

        return $this;
    }

    /**
     * Get banqdon
     *
     * @return integer
     */
    public function getBanqdon()
    {
        return $this->banqdon;
    }

    /**
     * Set moddon
     *
     * @param string $moddon
     *
     * @return Don
     */
    public function setModdon($moddon)
    {
        $this->moddon = $moddon;

        return $this;
    }

    /**
     * Get moddon
     *
     * @return string
     */
    public function getModdon()
    {
        return $this->moddon;
    }

    /**
     * Set nodonr
     *
     * @param integer $nodonr
     *
     * @return Don
     */
    public function setNodonr($nodonr)
    {
        $this->nodonr = $nodonr;

        return $this;
    }

    /**
     * Get nodonr
     *
     * @return integer
     */
    public function getNodonr()
    {
        return $this->nodonr;
    }

    /**
     * Set validdon
     *
     * @param boolean $validdon
     *
     * @return Don
     */
    public function setValiddon($validdon)
    {
        $this->validdon = $validdon;

        return $this;
    }

    /**
     * Get validdon
     *
     * @return boolean
     */
    public function getValiddon()
    {
        return $this->validdon;
    }

    /**
     * Set norecu
     *
     * @param boolean $norecu
     *
     * @return Don
     */
    public function setNorecu($norecu)
    {
        $this->norecu = $norecu;

        return $this;
    }

    /**
     * Get norecu
     *
     * @return boolean
     */
    public function getNorecu()
    {
        return $this->norecu;
    }

    /**
     * Set adhesion
     *
     * @param boolean $adhesion
     *
     * @return Don
     */
    public function setAdhesion($adhesion)
    {
        $this->adhesion = $adhesion;

        return $this;
    }

    /**
     * Get adhesion
     *
     * @return boolean
     */
    public function getAdhesion()
    {
        return $this->adhesion;
    }

    /**
     * Set memodon
     *
     * @param string $memodon
     *
     * @return Don
     */
    public function setMemodon($memodon)
    {
        $this->memodon = $memodon;

        return $this;
    }

    /**
     * Get memodon
     *
     * @return string
     */
    public function getMemodon()
    {
        return $this->memodon;
    }

    /**
     * Set datdon
     *
     * @param \DateTime $datdon
     *
     * @return Don
     */
    public function setDatdon($datdon)
    {
        $this->datdon = $datdon;

        return $this;
    }

    /**
     * Get datdon
     *
     * @return \DateTime
     */
    public function getDatdon()
    {
        return $this->datdon;
    }

    /**
     * Set datrecu
     *
     * @param \DateTime $datrecu
     *
     * @return Don
     */
    public function setDatrecu($datrecu)
    {
        $this->datrecu = $datrecu;

        return $this;
    }

    /**
     * Get datrecu
     *
     * @return \DateTime
     */
    public function getDatrecu()
    {
        return $this->datrecu;
    }

    /**
     * Set transdoncons
     *
     * @param string $transdon
     *
     * @return Don
     */
    public function setTransdon($transdon)
    {
        $this->transdon = $transdon;

        return $this;
    }

    /**
     * Get transdon
     *
     * @return string
     */
    public function getTransdon()
    {
        return $this->transdon;
    }

    /**
     * Set paysdon
     *
     * @param string $paysdon
     *
     * @return Don
     */
    public function setPaysdon($paysdon)
    {
        $this->paysdon = $paysdon;

        return $this;
    }

    /**
     * Get paysdon
     *
     * @return string
     */
    public function getPaysdon()
    {
        return $this->paysdon;
    }

    /**
     * Set msgbanq
     *
     * @param string $msgbanq
     *
     * @return Don
     */
    public function setMsgbanq($msgbanq)
    {
        $this->msgbanq = $msgbanq;

        return $this;
    }

    /**
     * Get msgbanq
     *
     * @return string
     */
    public function getMsgbanq()
    {
        return $this->msgbanq;
    }

    /**
     * Set enregdon
     *
     * @param \DateTime $enregdon
     *
     * @return Don
     */
    public function cons($enregdon)
    {
        $this->enregdon = $enregdon;

        return $this;
    }

    /**
     * Get enregdon
     *
     * @return \DateTime
     */
    public function getEnregdon()
    {
        return $this->enregdon;
    }

    /**
     * Set creatdon
     *
     * @param string $creatdon
     *
     * @return Don
     */
    public function setCreatdon($creatdon)
    {
        $this->creatdon = $creatdon;

        return $this;
    }

    /**
     * Get creatdon
     *
     * @return string
     */
    public function getCreatdon()
    {
        return $this->creatdon;
    }

    /**
     * Set olddon
     *
     * @param integer $olddon
     *
     * @return Don
     */
    public function setOlddon($olddon)
    {
        $this->olddon = $olddon;

        return $this;
    }

    /**
     * Get olddon
     *
     * @return integer
     */
    public function getOlddon()
    {
        return $this->olddon;
    }

    /**
     * Get coddon
     *
     * @return integer
     */
    public function getCoddon()
    {
        return $this->coddon;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Don
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Don
     */
    public function setContact(\AppBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set enregdon
     *
     * @param \DateTime $enregdon
     *
     * @return Don
     */
    public function setEnregdon($enregdon)
    {
        $this->enregdon = $enregdon;

        return $this;
    }
}
