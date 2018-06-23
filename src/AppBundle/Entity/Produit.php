<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit",
 * indexes={@ORM\Index(name="CodRub", columns={"CodRub"}), @ORM\Index(name="CodB", columns={"CodB"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodPrd", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codprd;

    /**
     * @var string
     *
     * @ORM\Column(name="RefPrd", type="string", length=20, nullable=false)
     */
    private $refprd;

    /**
     * @var string
     *
     * @ORM\Column(name="Produit", type="string", length=80, nullable=false)
     */
    private $produit;

    /**
     * @var int
     *
     * @ORM\Column(name="CodRub", type="integer", nullable=false)
     */
    private $codrub;

    /**
     * @var int
     *
     * @ORM\Column(name="CodB", type="integer", nullable=false)
     */
    private $codb;

    /**
     * @var string
     *
     * @ORM\Column(name="Isbn", type="string", length=13, nullable=false, options={"fixed"=true})
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="Serie", type="string", length=50, nullable=false)
     */
    private $serie;

    /**
     * @var string
     *
     * @ORM\Column(name="Auteur", type="string", length=50, nullable=false)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="TypPrd", type="string", length=8, nullable=false)
     */
    private $typprd;

    /**
     * @var string
     *
     * @ORM\Column(name="Annee", type="string", length=4, nullable=false)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="Prix", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="Promo", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $promo;

    /**
     * @var int
     *
     * @ORM\Column(name="Poids", type="smallint", nullable=false)
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="EtatPrd", type="string", length=6, nullable=false)
     */
    private $etatprd;

    /**
     * @var int
     *
     * @ORM\Column(name="Largeur", type="smallint", nullable=false)
     */
    private $largeur;

    /**
     * @var int
     *
     * @ORM\Column(name="Hauteur", type="smallint", nullable=false)
     */
    private $hauteur;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPage", type="smallint", nullable=false)
     */
    private $nbpage;

    /**
     * @var int
     *
     * @ORM\Column(name="Stock", type="smallint", nullable=false)
     */
    private $stock;

    /**
     * @var bool
     *
     * @ORM\Column(name="Hide", type="boolean", nullable=false)
     */
    private $hide;

    /**
     * @var string
     *
     * @ORM\Column(name="AdImg", type="text", length=65535, nullable=false)
     */
    private $adimg;

    /**
     * @var string
     *
     * @ORM\Column(name="AdImg2", type="text", length=65535, nullable=false)
     */
    private $adimg2;

    /**
     * @var string
     *
     * @ORM\Column(name="AdImg3", type="text", length=65535, nullable=false)
     */
    private $adimg3;

    /**
     * @var string
     *
     * @ORM\Column(name="urlBook", type="text", length=65535, nullable=false)
     */
    private $urlbook;

    /**
     * @var string
     *
     * @ORM\Column(name="PagePrd", type="text", length=65535, nullable=false)
     */
    private $pageprd;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoPrd", type="text", length=65535, nullable=false)
     */
    private $memoprd;

    /**
     * @var string
     *
     * @ORM\Column(name="Presentation", type="text", length=65535, nullable=true)
     */
    private $presentation;

    /**
     * @var string
     *
     * @ORM\Column(name="Enreg", type="text", length=65535, nullable=false)
     */
    private $enreg;

    /**
     * @var int
     *
     * @ORM\Column(name="Rang", type="smallint", nullable=false)
     */
    private $rang;

    /**
     * @var int
     *
     * @ORM\Column(name="Maj", type="datetime", nullable=true)
     */
    private $maj;

    /**
     * @var int
     *
     * @ORM\Column(name="Nouveaute", type="boolean", nullable=true)
     */
    private $nouveaute;

    /**
     * @var string
     *
     * @ORM\Column(name="Themes", type="text", nullable=false)
     */
    private $themes;

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
     * Set refprd.
     *
     * @param string $refprd
     *
     * @return Produit
     */
    public function setRefprd($refprd)
    {
        $this->refprd = $refprd;

        return $this;
    }

    /**
     * Get refprd.
     *
     * @return string
     */
    public function getRefprd()
    {
        return $this->refprd;
    }

    /**
     * Set produit.
     *
     * @param string $produit
     *
     * @return Produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit.
     *
     * @return string
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set codrub.
     *
     * @param int $codrub
     *
     * @return Produit
     */
    public function setCodrub($codrub)
    {
        $this->codrub = $codrub;

        return $this;
    }

    /**
     * Get codrub.
     *
     * @return int
     */
    public function getCodrub()
    {
        return $this->codrub;
    }

    /**
     * Set codb.
     *
     * @param int $codb
     *
     * @return Produit
     */
    public function setCodb($codb)
    {
        $this->codb = $codb;

        return $this;
    }

    /**
     * Get codb.
     *
     * @return int
     */
    public function getCodb()
    {
        return $this->codb;
    }

    /**
     * Set isbn.
     *
     * @param string $isbn
     *
     * @return Produit
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn.
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set serie.
     *
     * @param string $serie
     *
     * @return Produit
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie.
     *
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set auteur.
     *
     * @param string $auteur
     *
     * @return Produit
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur.
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set typprd.
     *
     * @param string $typprd
     *
     * @return Produit
     */
    public function setTypprd($typprd)
    {
        $this->typprd = $typprd;

        return $this;
    }

    /**
     * Get typprd.
     *
     * @return string
     */
    public function getTypprd()
    {
        return $this->typprd;
    }

    /**
     * Set annee.
     *
     * @param string $annee
     *
     * @return Produit
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee.
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set prix.
     *
     * @param string $prix
     *
     * @return Produit
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
     * Set promo.
     *
     * @param string $promo
     *
     * @return Produit
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
     * Set poids.
     *
     * @param int $poids
     *
     * @return Produit
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids.
     *
     * @return int
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set etatprd.
     *
     * @param string $etatprd
     *
     * @return Produit
     */
    public function setEtatprd($etatprd)
    {
        $this->etatprd = $etatprd;

        return $this;
    }

    /**
     * Get etatprd.
     *
     * @return string
     */
    public function getEtatprd()
    {
        return $this->etatprd;
    }

    /**
     * Set largeur.
     *
     * @param int $largeur
     *
     * @return Produit
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur.
     *
     * @return int
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    /**
     * Set hauteur.
     *
     * @param int $hauteur
     *
     * @return Produit
     */
    public function setHauteur($hauteur)
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    /**
     * Get hauteur.
     *
     * @return int
     */
    public function getHauteur()
    {
        return $this->hauteur;
    }

    /**
     * Set nbpage.
     *
     * @param int $nbpage
     *
     * @return Produit
     */
    public function setNbpage($nbpage)
    {
        $this->nbpage = $nbpage;

        return $this;
    }

    /**
     * Get nbpage.
     *
     * @return int
     */
    public function getNbpage()
    {
        return $this->nbpage;
    }

    /**
     * Set stock.
     *
     * @param int $stock
     *
     * @return Produit
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock.
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set hide.
     *
     * @param bool $hide
     *
     * @return Produit
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Get hide.
     *
     * @return bool
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * Set adimg.
     *
     * @param string $adimg
     *
     * @return Produit
     */
    public function setAdimg($adimg)
    {
        $this->adimg = $adimg;

        return $this;
    }

    /**
     * Get adimg.
     *
     * @return string
     */
    public function getAdimg()
    {
        return $this->adimg;
    }

    /**
     * Set adimg2.
     *
     * @param string $adimg2
     *
     * @return Produit
     */
    public function setAdimg2($adimg2)
    {
        $this->adimg2 = $adimg2;

        return $this;
    }

    /**
     * Get adimg2.
     *
     * @return string
     */
    public function getAdimg2()
    {
        return $this->adimg2;
    }

    /**
     * Set adimg3.
     *
     * @param string $adimg3
     *
     * @return Produit
     */
    public function setAdimg3($adimg3)
    {
        $this->adimg3 = $adimg3;

        return $this;
    }

    /**
     * Get adimg3.
     *
     * @return string
     */
    public function getAdimg3()
    {
        return $this->adimg3;
    }

    /**
     * Set urlbook.
     *
     * @param string $urlbook
     *
     * @return Produit
     */
    public function setUrlbook($urlbook)
    {
        $this->urlbook = $urlbook;

        return $this;
    }

    /**
     * Get urlbook.
     *
     * @return string
     */
    public function getUrlbook()
    {
        return $this->urlbook;
    }

    /**
     * Set pageprd.
     *
     * @param string $pageprd
     *
     * @return Produit
     */
    public function setPageprd($pageprd)
    {
        $this->pageprd = $pageprd;

        return $this;
    }

    /**
     * Get pageprd.
     *
     * @return string
     */
    public function getPageprd()
    {
        return $this->pageprd;
    }

    /**
     * Set memoprd.
     *
     * @param string $memoprd
     *
     * @return Produit
     */
    public function setMemoprd($memoprd)
    {
        $this->memoprd = $memoprd;

        return $this;
    }

    /**
     * Get memoprd.
     *
     * @return string
     */
    public function getMemoprd()
    {
        return $this->memoprd;
    }

    /**
     * Set presentation.
     *
     * @param string|null $presentation
     *
     * @return Produit
     */
    public function setPresentation($presentation = null)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation.
     *
     * @return string|null
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set enreg.
     *
     * @param string $enreg
     *
     * @return Produit
     */
    public function setEnreg($enreg)
    {
        $this->enreg = $enreg;

        return $this;
    }

    /**
     * Get enreg.
     *
     * @return string
     */
    public function getEnreg()
    {
        return $this->enreg;
    }

    /**
     * Set rang.
     *
     * @param int $rang
     *
     * @return Produit
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang.
     *
     * @return int
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set maj.
     *
     * @param \DateTime|null $maj
     *
     * @return Produit
     */
    public function setMaj($maj = null)
    {
        $this->maj = $maj;

        return $this;
    }

    /**
     * Get maj.
     *
     * @return \DateTime|null
     */
    public function getMaj()
    {
        return $this->maj;
    }

    /**
     * Set nouveaute.
     *
     * @param bool|null $nouveaute
     *
     * @return Produit
     */
    public function setNouveaute($nouveaute = null)
    {
        $this->nouveaute = $nouveaute;

        return $this;
    }

    /**
     * Get nouveaute.
     *
     * @return bool|null
     */
    public function getNouveaute()
    {
        return $this->nouveaute;
    }

    /**
     * Set themes.
     *
     * @param string $themes
     *
     * @return Produit
     */
    public function setThemes($themes)
    {
        $this->themes = $themes;

        return $this;
    }

    /**
     * Get themes.
     *
     * @return string
     */
    public function getThemes()
    {
        return $this->themes;
    }
}
