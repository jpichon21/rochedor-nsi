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
}
