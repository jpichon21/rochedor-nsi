<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="CodCli", columns={"CodCli"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCom", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcom;

    /**
     * @var int
     *
     * @ORM\Column(name="CodCli", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $codcli;

    /**
     * @var string
     *
     * @ORM\Column(name="RefCom", type="string", length=20, nullable=false)
     */
    private $refcom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatCom", type="date", nullable=false)
     */
    private $datcom;

    /**
     * @var string
     *
     * @ORM\Column(name="Montant", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="ModPaie", type="string", length=6, nullable=false)
     */
    private $modpaie;

    /**
     * @var string
     *
     * @ORM\Column(name="ModLiv", type="string", length=6, nullable=false)
     */
    private $modliv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatPaie", type="datetime", nullable=false)
     */
    private $datpaie;

    /**
     * @var string
     *
     * @ORM\Column(name="ValidPaie", type="string", length=12, nullable=false)
     */
    private $validpaie;

    /**
     * @var string
     *
     * @ORM\Column(name="DestLiv", type="string", length=6, nullable=false, options={"fixed"=true})
     */
    private $destliv;

    /**
     * @var array
     *
     * @ORM\Column(name="AdLiv", type="json", length=65535)
     */
    private $adliv;

    /**
     * @var string
     *
     * @ORM\Column(name="TTC", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $ttc;

    /**
     * @var string
     *
     * @ORM\Column(name="TVA", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $tva;

    /**
     * @var string
     *
     * @ORM\Column(name="Poids", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="Port", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(name="Promo", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $promo;

    /**
     * @var string
     *
     * @ORM\Column(name="TextCmd", type="text", length=65535, nullable=false)
     */
    private $textcmd;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoCmd", type="text", length=65535, nullable=false)
     */
    private $memocmd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatLiv", type="date", nullable=false)
     */
    private $datliv;

    /**
     * @var string
     *
     * @ORM\Column(name="PaysIP", type="string", length=3, nullable=false)
     */
    private $paysip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatEnreg", type="datetime", nullable=false)
     */
    private $datenreg;

    /**
     * @var array
     *
     * @ORM\Column(name="AdFact", type="json", length=65535)
     */
    private $adFact;

    public function __construct()
    {
        $this->montant = 0;
        $this->modpaie = '';
        $this->modliv = '';
        $this->datpaie = new \DateTime('0000-00-00');
        $this->validpaie = '';
        $this->destliv = '';
        $this->adliv = '';
        $this->adFact= '';
        $this->ttc = 0;
        $this->tva = 0;
        $this->poids = 0;
        $this->port = 0;
        $this->promo = 0;
        $this->textcmd = '';
        $this->memocmd = '';
        $this->datliv = new \DateTime('0000-00-00');
        $this->paysip = '';
        $this->datenreg = new \DateTime('0000-00-00');
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
     * Set codcli.
     *
     * @param int $codcli
     *
     * @return Commande
     */
    public function setCodcli($codcli)
    {
        $this->codcli = $codcli;

        return $this;
    }

    /**
     * Get codcli.
     *
     * @return int
     */
    public function getCodcli()
    {
        return $this->codcli;
    }

    /**
     * Set refcom.
     *
     * @param string $refcom
     *
     * @return Commande
     */
    public function setRefcom($refcom)
    {
        $this->refcom = $refcom;

        return $this;
    }

    /**
     * Get refcom.
     *
     * @return string
     */
    public function getRefcom()
    {
        return $this->refcom;
    }

    /**
     * Set datcom.
     *
     * @param \DateTime $datcom
     *
     * @return Commande
     */
    public function setDatcom($datcom)
    {
        $this->datcom = $datcom;

        return $this;
    }

    /**
     * Get datcom.
     *
     * @return \DateTime
     */
    public function getDatcom()
    {
        return $this->datcom;
    }

    /**
     * Set montant.
     *
     * @param string $montant
     *
     * @return Commande
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant.
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set modpaie.
     *
     * @param string $modpaie
     *
     * @return Commande
     */
    public function setModpaie($modpaie)
    {
        $this->modpaie = $modpaie;

        return $this;
    }

    /**
     * Get modpaie.
     *
     * @return string
     */
    public function getModpaie()
    {
        return $this->modpaie;
    }

    /**
     * Set modliv.
     *
     * @param string $modliv
     *
     * @return Commande
     */
    public function setModliv($modliv)
    {
        $this->modliv = $modliv;

        return $this;
    }

    /**
     * Get modliv.
     *
     * @return string
     */
    public function getModliv()
    {
        return $this->modliv;
    }

    /**
     * Set datpaie.
     *
     * @param \DateTime $datpaie
     *
     * @return Commande
     */
    public function setDatpaie($datpaie)
    {
        $this->datpaie = $datpaie;

        return $this;
    }

    /**
     * Get datpaie.
     *
     * @return \DateTime
     */
    public function getDatpaie()
    {
        return $this->datpaie;
    }

    /**
     * Set validpaie.
     *
     * @param string $validpaie
     *
     * @return Commande
     */
    public function setValidpaie($validpaie)
    {
        $this->validpaie = $validpaie;

        return $this;
    }

    /**
     * Get validpaie.
     *
     * @return string
     */
    public function getValidpaie()
    {
        return $this->validpaie;
    }

    /**
     * Set destliv.
     *
     * @param string $destliv
     *
     * @return Commande
     */
    public function setDestliv($destliv)
    {
        $this->destliv = $destliv;

        return $this;
    }

    /**
     * Get destliv.
     *
     * @return string
     */
    public function getDestliv()
    {
        return $this->destliv;
    }

    /**
     * Set adliv.
     *
     * @param string $adliv
     *
     * @return Commande
     */
    public function setAdliv($adliv)
    {
        $this->adliv = $adliv;

        return $this;
    }

    /**
     * Get adliv.
     *
     * @return string
     */
    public function getAdliv()
    {
        return $this->adliv;
    }

    /**
     * Set ttc.
     *
     * @param string $ttc
     *
     * @return Commande
     */
    public function setTtc($ttc)
    {
        $this->ttc = $ttc;

        return $this;
    }

    /**
     * Get ttc.
     *
     * @return string
     */
    public function getTtc()
    {
        return $this->ttc;
    }

    /**
     * Set tva.
     *
     * @param string $tva
     *
     * @return Commande
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva.
     *
     * @return string
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set poids.
     *
     * @param string $poids
     *
     * @return Commande
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids.
     *
     * @return string
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set port.
     *
     * @param string $port
     *
     * @return Commande
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port.
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set promo.
     *
     * @param string $promo
     *
     * @return Commande
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo.
     *
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set textcmd.
     *
     * @param string $textcmd
     *
     * @return Commande
     */
    public function setTextcmd($textcmd)
    {
        $this->textcmd = $textcmd;

        return $this;
    }

    /**
     * Get textcmd.
     *
     * @return string
     */
    public function getTextcmd()
    {
        return $this->textcmd;
    }

    /**
     * Set memocmd.
     *
     * @param string $memocmd
     *
     * @return Commande
     */
    public function setMemocmd($memocmd)
    {
        $this->memocmd = $memocmd;

        return $this;
    }

    /**
     * Get memocmd.
     *
     * @return string
     */
    public function getMemocmd()
    {
        return $this->memocmd;
    }

    /**
     * Set datliv.
     *
     * @param \DateTime $datliv
     *
     * @return Commande
     */
    public function setDatliv($datliv)
    {
        $this->datliv = $datliv;

        return $this;
    }

    /**
     * Get datliv.
     *
     * @return \DateTime
     */
    public function getDatliv()
    {
        return $this->datliv;
    }

    /**
     * Set paysip.
     *
     * @param string $paysip
     *
     * @return Commande
     */
    public function setPaysip($paysip)
    {
        $this->paysip = $paysip;

        return $this;
    }

    /**
     * Get paysip.
     *
     * @return string
     */
    public function getPaysip()
    {
        return $this->paysip;
    }

    /**
     * Set datenreg.
     *
     * @param \DateTime $datenreg
     *
     * @return Commande
     */
    public function setDatenreg($datenreg)
    {
        $this->datenreg = $datenreg;

        return $this;
    }

    /**
     * Get datenreg.
     *
     * @return \DateTime
     */
    public function getDatenreg()
    {
        return $this->datenreg;
    }

    public function getAdFact()
    {
        return $this->adFact;
    }

    /**
     * Set adFact.
     *
     * @param array $adFact
     *
     * @return Commande
     */
    public function setAdFact($adFact)
    {
        $this->adFact = $adFact;

        return $this;
    }
}
