<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact", indexes={@ORM\Index(name="CodB", columns={"CodB"})})
 * @ORM\Entity
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codco;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CodB", type="integer", nullable=true)
     */
    private $codb;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="TypCo", type="boolean", nullable=true)
     */
    private $typco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DivCo", type="string", length=6, nullable=true)
     */
    private $divco;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SelCo", type="integer", nullable=true)
     */
    private $selco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ident", type="string", length=256, nullable=true)
     */
    private $ident;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Civil", type="string", length=6, nullable=true)
     */
    private $civil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Civil2", type="string", length=9, nullable=true)
     */
    private $civil2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Nom", type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Prenom", type="string", length=25, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Adresse", type="text", length=65535, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CP", type="string", length=8, nullable=true)
     */
    private $cp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ville", type="string", length=35, nullable=true)
     */
    private $ville;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Pays", type="string", length=20, nullable=true)
     */
    private $pays;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Tel", type="string", length=20, nullable=true)
     */
    private $tel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Mobil", type="string", length=20, nullable=true)
     */
    private $mobil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="eMail", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Societe", type="string", length=40, nullable=true)
     */
    private $societe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Profession", type="text", length=65535, nullable=true)
     */
    private $profession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mpCo", type="string", length=15, nullable=true)
     */
    private $mpco;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DatNaiss", type="date", nullable=true)
     */
    private $datnaiss;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Zone", type="integer", nullable=true)
     */
    private $zone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Libre", type="string", length=6, nullable=true)
     */
    private $libre;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="noLet", type="boolean", nullable=true)
     */
    private $nolet;

    /**
     * @var int|null
     *
     * @ORM\Column(name="RangCo", type="smallint", nullable=true)
     */
    private $rangco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CreatCo", type="string", length=15, nullable=true)
     */
    private $creatco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="JSCo", type="text", length=65535, nullable=true)
     */
    private $jsco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImgCo", type="text", length=65535, nullable=true)
     */
    private $imgco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="MemoCo", type="text", length=65535, nullable=true)
     */
    private $memoco;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="aboLet", type="boolean", nullable=true)
     */
    private $abolet;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Particips", type="text", length=65535, nullable=true)
     */
    private $particips;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CatCo", type="string", length=1, nullable=true, options={"fixed"=true})
     */
    private $catco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CatCo2", type="string", length=1, nullable=true, options={"fixed"=true})
     */
    private $catco2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Csp", type="integer", nullable=true)
     */
    private $csp;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DatDem", type="date", nullable=true)
     */
    private $datdem;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Infop", type="text", length=65535, nullable=true)
     */
    private $infop;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Infolg", type="text", length=65535, nullable=true)
     */
    private $infolg;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="EnregCo", type="datetime", nullable=true)
     */
    private $enregco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TempCo", type="text", length=65535, nullable=true)
     */
    private $tempco;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=256, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Region", type="string", length=200, nullable=true)
     */
    private $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="plainPassword", type="string", length=255, nullable=true)
     */
    private $plainpassword;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var bool
     *
     * @ORM\Column(name="letOca", type="boolean", nullable=false, options={"comment"="Lettre occasionnelle"})
     */
    private $letoca;

    /**
     * @var bool
     *
     * @ORM\Column(name="letPaper", type="boolean", nullable=false, options={"comment"="Lettre annuelle papier"})
     */
    private $letpaper;

    /**
     * @var bool
     *
     * @ORM\Column(name="letMail", type="boolean", nullable=false, options={"comment"="Lettre annuelle par mail"})
     */
    private $letmail;

    /**
     * @var bool
     *
     * @ORM\Column(name="aut16", type="boolean", nullable=false)
     */
    private $aut16;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetOca", type="datetime", nullable=true, options={"comment"="Lettre occasionnelle"})
     */
    private $datletoca;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetPaper", type="datetime", nullable=true, options={"comment"="Lettre annuelle papier"})
     */
    private $datletpaper;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetMail", type="datetime", nullable=true, options={"comment"="Lettre annuelle par mail"})
     */
    private $datletmail;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datAut16", type="date", nullable=true)
     */
    private $dataut16;
}
