<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DonR
 *
 * @ORM\Table(
 * name="donr",
 * indexes={
 *     @ORM\Index(name="CodDonR", columns={"CodDonR"}),
 *     @ORM\Index(name="DestDon", columns={"DestDon"}),
 *     @ORM\Index(name="DonRCo", columns={"DonRCo"}),
 *     @ORM\Index(name="BanqDon", columns={"BanqDon"})
 * })
 * @ORM\Entity
 */
class DonR
{
    /**
     * @var integer
     *
     * @ORM\Column(name="CodDonR", type="integer", length=11, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codDonR;
    
    /**
     * @var string
     *
     * @ORM\Column(name="RefDon", type="string", length=9, nullable=false)
     */
    private $refdon;

    /**
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumn(name="DonRCo", referencedColumnName="CodCo")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="DestDon", type="string", length=6, nullable=false)
     */
    private $destdon;

    /**
     * @var string
     *
     * @ORM\Column(name="MntDon", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $mntdon;

    /**
     * @var string
     *
     * @ORM\Column(name="MonDonR", type="string", length=2, nullable=false)
     */
    private $mondonR;

    /**
     * @var integer
     *
     * @ORM\Column(name="BanqDon", type="integer", length=11, nullable=false)
     */
    private $banqdon;

    /**
     * @var string
     *
     * @ORM\Column(name="Banque", type="string", length=45, nullable=false)
     */
    private $banque;

    /**
     * @var string
     *
     * @ORM\Column(name="ModDonR", type="string", length=6, nullable=false)
     */
    private $moddonr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatVir", type="datetime", nullable=false)
     */
    private $datVir;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="VirFin", type="datetime", nullable=false)
     */
    private $virFin;

    /**
     * @var string
     *
     * @ORM\Column(name="VirFreq", type="string", length=1, nullable=false)
     */
    private $virFreq;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatTrans", type="date", nullable=false)
     */
    private $datTrans;

    /**
     * @var string
     *
     * @ORM\Column(name="CreatDonR", type="string", length=15, nullable=false)
     */
    private $creatdonR;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EnregDonR", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $enregdonR = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false)
     */
    private $status;

    public function __construct()
    {
        $this->enregdonR = new \DateTime();
        $this->refdon = '';
        $this->mntdon = 0;
        $this->mondonR = '';
        $this->destdon = '';
        $this->banqdon = 0;
        $this->moddonr = '';
        $this->creatdonR = '';
        $this->status = '';
        $this->banque = '';
        $this->datVir = new \DateTime();
        $this->virFin = new \DateTime();
        $this->virFreq = '';
        $this->datTrans = new \DateTime();
    }

    /**
     * Set refdon
     *
     * @param string $refdon
     *
     * @return DonR
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
     * @return DonR
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
     * @param string $mondonR
     *
     * @return DonR
     */
    public function setMondonR($mondonR)
    {
        $this->mondonR = $mondonR;

        return $this;
    }

    /**
     * Get mondon
     *
     * @return string
     */
    public function getMondonR()
    {
        return $this->mondonR;
    }

    /**
     * Set destdon
     *
     * @param string $destdon
     *
     * @return DonR
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
     * @return DonR
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
     * Set moddonr
     *
     * @param string $moddonr
     *
     * @return DonR
     */
    public function setModdonr($moddonr)
    {
        $this->moddonr = $moddonr;

        return $this;
    }

    /**
     * Get moddon
     *
     * @return string
     */
    public function getModdonr()
    {
        return $this->moddonr;
    }

    /**
     * Set enregdon
     *
     * @param \DateTime $enregdon
     *
     * @return DonR
     */
    public function cons($enregdon)
    {
        $this->enregdonR = $enregdon;

        return $this;
    }

    /**
     * Get enregdon
     *
     * @return \DateTime
     */
    public function getEnregdonR()
    {
        return $this->enregdonR;
    }

    /**
     * Set creatdon
     *
     * @param string $creatdonR
     *
     * @return DonR
     */
    public function setCreatdonR($creatdonR)
    {
        $this->creatdonR = $creatdonR;

        return $this;
    }

    /**
     * Get creatdon
     *
     * @return string
     */
    public function getCreatdonR()
    {
        return $this->creatdonR;
    }

    /**
     * Get CodDonR
     *
     * @return integer
     */
    public function getCodDonR()
    {
        return $this->codDonR;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return DonR
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
     * @return DonR
     */
    public function setContact($contact = null)
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
     * @param \DateTime $enregdonR
     *
     * @return DonR
     */
    public function setEnregdonR($enregdonR)
    {
        $this->enregdonR = $enregdonR;

        return $this;
    }

    /**
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * @param string $banque
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatVir()
    {
        return $this->datVir;
    }

    /**
     * @param \DateTime $datVir
     */
    public function setDatVir($datVir)
    {
        $this->datVir = $datVir;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVirFin()
    {
        return $this->virFin;
    }

    /**
     * @param \DateTime $virFin
     */
    public function setVirFin($virFin)
    {
        $this->virFin = $virFin;

        return $this;
    }

    /**
     * @return string
     */
    public function getVirFreq()
    {
        return $this->virFreq;
    }

    /**
     * @param string $virFreq
     */
    public function setVirFreq($virFreq)
    {
        $this->virFreq = $virFreq;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatTrans()
    {
        return $this->datTrans;
    }

    /**
     * @param \DateTime $datTrans
     */
    public function setDatTrans($datTrans)
    {
        $this->datTrans = $datTrans;

        return $this;
    }
}
