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
     * @ORM\Column(name="Produitcourt", type="string", length=80, nullable=false)
     */
    private $produitcourt;

    /**
     * @var string
     *
     * @ORM\Column(name="Produitlong", type="text", nullable=true)
     */
    private $produitlong;

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
     * @ORM\Column(name="Ean", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $ean;

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
     * @ORM\Column(name="Editeur", type="string", length=255, nullable=true)
     */
    private $editeur;

    /**
     * @var string
     *
     * @ORM\Column(name="TypPrd", type="string", length=8, nullable=false)
     */
    private $typprd;

    /**
     * @var string
     *
     * @ORM\Column(name="Dateparution", type="datetime", nullable=true)
     */
    private $dateparution;

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
     * @ORM\Column(name="Epaisseur", type="smallint", nullable=false)
     */
    private $epaisseur;

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
     * Get refprd.
     *
     * @return string
     */
    public function getRefprd()
    {
        return $this->refprd;
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
     * Get codrub.
     *
     * @return int
     */
    public function getCodrub()
    {
        return $this->codrub;
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
     * Get isbn.
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
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
     * Get auteur.
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
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
     * Get annee.
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
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
     * Get promo.
     *
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
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
     * Get etatprd.
     *
     * @return string
     */
    public function getEtatprd()
    {
        return $this->etatprd;
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
     * Get hauteur.
     *
     * @return int
     */
    public function getHauteur()
    {
        return $this->hauteur;
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
     * Get stock.
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
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
     * Get adimg.
     *
     * @return string
     */
    public function getAdimg()
    {
        return $this->adimg;
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
     * Get adimg3.
     *
     * @return string
     */
    public function getAdimg3()
    {
        return $this->adimg3;
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
     * Get pageprd.
     *
     * @return string
     */
    public function getPageprd()
    {
        return $this->pageprd;
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
     * Get presentation.
     *
     * @return string|null
     */
    public function getPresentation()
    {
        return $this->presentation;
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
     * Get rang.
     *
     * @return int
     */
    public function getRang()
    {
        return $this->rang;
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
     * Get nouveaute.
     *
     * @return bool|null
     */
    public function getNouveaute()
    {
        return $this->nouveaute;
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

    /**
     * Get produitcourt.
     *
     * @return string
     */
    public function getProduitcourt()
    {
        return $this->produitcourt;
    }

    /**
     * Get produitlong.
     *
     * @return string|null
     */
    public function getProduitlong()
    {
        return $this->produitlong;
    }

    /**
     * Get ean.
     *
     * @return string|null
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Get editeur.
     *
     * @return string|null
     */
    public function getEditeur()
    {
        return $this->editeur;
    }

    /**
     * Get dateparution.
     *
     * @return \DateTime|null
     */
    public function getDateparution()
    {
        return $this->dateparution;
    }

    /**
     * Get epaisseur.
     *
     * @return int
     */
    public function getEpaisseur()
    {
        return $this->epaisseur;
    }
}
