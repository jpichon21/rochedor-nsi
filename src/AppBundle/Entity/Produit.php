<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit",
 * indexes={@ORM\Index(name="CodRub", columns={"CodRub"}), @ORM\Index(name="RangSelect", columns={"RangSelect"})},
 * options={"engine":"MyISAM"})
 * )
 * @ORM\Entity
 */
class Produit
{
    const GENDER = [
        'autbio' => 'Autobiographie',
        'carnot' => 'Carnet de notes',
        'chant' => 'Chants',
        'cmtbib' => 'Commentaire biblique',
        'floril' => 'Florilège',
        'lectxt' => 'Lecture de textes',
        'priere' => 'Prière',
        'temoin' => 'Témoignage',
    ];

    const TYPE_LIVRE = 'livre';
    const TYPE_LIVRE_AUDIO = 'livreaud';
    const TYPE_LIVRET_PARTITION = 'livrepar';
    const TYPE_CD = 'cd';

    const TYP_PRD = [
        self::TYPE_LIVRE => 'Livre',
        self::TYPE_LIVRE_AUDIO => 'Livre audio',
        self::TYPE_LIVRET_PARTITION => 'Livret de partitions',
        self::TYPE_CD => 'CD'
    ];

    const STATUT_DISPO = '';
    const STATUT_EPUISE = 'epuise';
    const STATUT_PERIME = 'reimp';

    const ETATPRD = [
        self::STATUT_DISPO => 'Disponible',
        self::STATUT_EPUISE => 'Epuisé',
        self::STATUT_PERIME => 'En réimpression'
    ];

    const LOCALES = [
        'FR' => 'Français',
        'EN' => 'Anglais',
        'IT' => 'Italien',
        'DE' => 'Allemand',
        'ES' => 'Espagnol'
    ];

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
    private $produitcourt;

    /**
     * @var string
     *
     * @ORM\Column(name="Produitlong", type="text", nullable=false)
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
     * @ORM\Column(name="RangSelect", type="integer", nullable=false)
     */
    private $rangSelect;

    /**
     * @var string
     *
     * @ORM\Column(name="Isbn", type="string", length=13, nullable=false, options={"fixed"=true})
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="Ean", type="string", length=20, nullable=false, options={"fixed"=true})
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
     * @ORM\Column(name="Editeur", type="string", length=40, nullable=false)
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
     * @ORM\Column(name="Datparution", type="datetime", nullable=false)
     */
    private $datparution;

    /**
     * @var string
     *
     * @ORM\Column(name="Prix", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="PrixHt", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $prixht;

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
     * @ORM\Column(name="NbPage", type="smallint", nullable=false)
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
     * @ORM\Column(name="UrlBook", type="text", length=65535, nullable=false)
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
     * @ORM\Column(name="Presentation", type="text", length=65535, nullable=false)
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="Nouveaute", type="boolean", nullable=false)
     */
    private $nouveaute;

    /**
     * @var string
     *
     * @ORM\Column(name="Themes", type="text", length=150, nullable=false)
     */
    private $themes;

    /**
     * @var string
     *
     * @ORM\Column(name="UrlPartenaire", type="text", nullable=false)
     */
    private $urlPartenaire;

    /**
     * @var string
     *
     * @ORM\Column(name="Genre", type="string", length=6, nullable=false, options={"fixed" = true})
     */
    private $genre;

    public function __construct()
    {
        $this->refprd = 0;
        $this->produitcourt = '';
        $this->produitlong = '';
        $this->codrub = 0;
        $this->rangSelect = 0;
        $this->isbn = 0;
        $this->ean = 0;
        $this->serie = '';
        $this->auteur = '';
        $this->editeur = '';
        $this->typprd = '';
        $this->datparution = new \DateTime();
        $this->prix = 0;
        $this->prixht = 0;
        $this->promo = 0;
        $this->poids = 0;
        $this->etatprd = '';
        $this->largeur = 0;
        $this->hauteur = 0;
        $this->epaisseur = 0;
        $this->nbpage = 0;
        $this->stock = 0;
        $this->hide = false;
        $this->adimg = '';
        $this->adimg2 = '';
        $this->adimg3 = '';
        $this->urlbook = '';
        $this->pageprd = '';
        $this->memoprd = '';
        $this->presentation = '';
        $this->enreg = '';
        $this->rang = 0;
        $this->nouveaute = false;
        $this->themes = '';
        $this->urlPartenaire = '';
        $this->genre = '';
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
     * Get rangSelect.
     *
     * @return int
     */
    public function getRangSelect()
    {
        return $this->rangSelect;
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
     * Get datparution.
     *
     * @return \DateTime|null
     */
    public function getDatparution()
    {
        return $this->datparution;
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
     * Set produitcourt.
     *
     * @param string $produitcourt
     *
     * @return Produit
     */
    public function setProduitcourt($produitcourt)
    {
        $this->produitcourt = $produitcourt;

        return $this;
    }

    /**
     * Set produitlong.
     *
     * @param string|null $produitlong
     *
     * @return Produit
     */
    public function setProduitlong($produitlong = null)
    {
        $this->produitlong = $produitlong;

        return $this;
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
     * Set rangSelect.
     *
     * @param int $rangSelect
     *
     * @return Produit
     */
    public function setRangSelect($rangSelect)
    {
        $this->rangSelect = $rangSelect;

        return $this;
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
     * Set ean.
     *
     * @param string|null $ean
     *
     * @return Produit
     */
    public function setEan($ean = null)
    {
        $this->ean = $ean;

        return $this;
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
     * Set editeur.
     *
     * @param string|null $editeur
     *
     * @return Produit
     */
    public function setEditeur($editeur = null)
    {
        $this->editeur = $editeur;

        return $this;
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
     * Set datparution.
     *
     * @param \DateTime|null $datparution
     *
     * @return Produit
     */
    public function setDatparution($datparution = null)
    {
        $this->datparution = $datparution;

        return $this;
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
     * Set prixht.
     *
     * @param string $prixht
     *
     * @return Produit
     */
    public function setPrixht($prixht)
    {
        $this->prixht = $prixht;

        return $this;
    }

    /**
     * Get prixht.
     *
     * @return string
     */
    public function getPrixht()
    {
        return $this->prixht;
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
     * Set epaisseur.
     *
     * @param int $epaisseur
     *
     * @return Produit
     */
    public function setEpaisseur($epaisseur)
    {
        $this->epaisseur = $epaisseur;

        return $this;
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
     * @return string
     */
    public function getUrlPartenaire()
    {
        return $this->urlPartenaire;
    }

    /**
     * @param string $urlPartenaire
     *
     * @return Produit
     */
    public function setUrlPartenaire($urlPartenaire)
    {
        $this->urlPartenaire = $urlPartenaire;

        return $this;
    }

    /**
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     *
     * @return Produit
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }
}
