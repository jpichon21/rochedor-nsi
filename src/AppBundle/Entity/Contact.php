<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Contact
 *
 * @ORM\Table(name="contact", indexes={@ORM\Index(name="CodB", columns={"CodB"})})
 * @ORM\Entity
 * @UniqueEntity("username", message="validation.username.already_used")
 * @ExclusionPolicy("all")
 */
class Contact implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $codco;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CodB", type="integer")
     * @Expose
     */
    private $codb = 0;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="TypCo", type="boolean")
     */
    private $typco = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DivCo", type="string", length=6)
     */
    private $divco = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="SelCo", type="integer")
     */
    private $selco = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ident", type="string", length=256)
     */
    private $ident = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Civil", type="string", length=6)
     * @Expose
     */
    private $civil = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Civil2", type="string", length=9)
     * @Expose
     */
    private $civil2 = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Nom", type="string", length=30)
     * @Assert\Length(min=3)
     * @Expose
     */
    private $nom = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Prenom", type="string", length=25)
     * @Expose
     */
    private $prenom = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Adresse", type="text", length=65535)
     * @Expose
     */
    private $adresse = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="CP", type="string", length=8)
     * @Expose
     */
    private $cp = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ville", type="string", length=35)
     * @Expose
     */
    private $ville = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Pays", type="string", length=20)
     * @Expose
     */
    private $pays = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Tel", type="string", length=20)
     * @Expose
     */
    private $tel = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Mobil", type="string", length=20)
     * @Expose
     */
    private $mobil = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="eMail", type="string", length=50)
     * @Expose
     */
    private $email = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Societe", type="string", length=40)
     */
    private $societe = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Profession", type="text", length=65535)
     * @Expose
     */
    private $profession = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="mpCo", type="string", length=15)
     */
    private $mpco = '';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DatNaiss", type="date")
     * @Expose
     */
    private $datnaiss;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Zone", type="integer")
     */
    private $zone = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Libre", type="string", length=6)
     */
    private $libre = '';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="noLet", type="boolean")
     */
    private $nolet = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="RangCo", type="smallint")
     */
    private $rangco = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CreatCo", type="string", length=15)
     */
    private $creatco = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="JSCo", type="text", length=65535)
     */
    private $jsco = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImgCo", type="text", length=65535)
     */
    private $imgco = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="MemoCo", type="text", length=65535)
     */
    private $memoco = '';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="aboLet", type="boolean")
     */
    private $abolet = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Particips", type="text", length=65535)
     */
    private $particips = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="CatCo", type="string", length=1, options={"fixed"=true})
     */
    private $catco = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="CatCo2", type="string", length=1, options={"fixed"=true})
     */
    private $catco2 = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="Csp", type="integer")
     */
    private $csp = 0;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DatDem", type="date")
     */
    private $datdem;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Infop", type="text", length=65535)
     */
    private $infop = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Infolg", type="text", length=65535)
     */
    private $infolg = '';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="EnregCo", type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $enregco;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TempCo", type="text", length=65535)
     */
    private $tempco = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60)
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "validation.password.length"
     * )
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Region", type="string", length=50)
     */
    private $region = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="plainPassword", type="string", length=255)
     */
    private $plainpassword = '';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, unique=true, nullable=true)
     * @Expose
     */
    private $username;

    /**
     * @var bool
     *
     * @ORM\Column(name="letOca", type="boolean", nullable=false, options={"comment"="Lettre occasionnelle"})
     */
    private $letoca = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="letPaper", type="boolean", nullable=false, options={"comment"="Lettre annuelle papier"})
     */
    private $letpaper = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="letMail", type="boolean", nullable=false, options={"comment"="Lettre annuelle par mail"})
     */
    private $letmail = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aut16", type="boolean", nullable=false)
     */
    private $aut16 = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetOca", type="datetime", options={"comment"="Lettre occasionnelle"})
     */
    private $datletoca;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetPaper", type="datetime", options={"comment"="Lettre annuelle papier"})
     */
    private $datletpaper;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datLetMail", type="datetime", options={"comment"="Lettre annuelle par mail"})
     */
    private $datletmail;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datAut16", type="date")
     */
    private $dataut16;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json", length=255)
     * @Type("array")
     * @Expose
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string
     *
     * @ORM\Column(name="resetToken", type="string", length=50, nullable=true)
     */
    private $resetToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resetTokenExpire", type="datetime")
     */
    private $resetTokenExpiresAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="NewFich", type="boolean", nullable=false)
     */
    private $newFich = false;

    public function __construct()
    {
        $this->datnaiss = new \DateTime('0000-00-00');
        $this->datdem = new \DateTime('0000-00-00');
        $this->enregco = new \DateTime();
        $this->datletoca = new \DateTime('0000-00-00');
        $this->datletpaper = new \DateTime('0000-00-00');
        $this->datletmail = new \DateTime('0000-00-00');
        $this->dataut16 = new \DateTime('0000-00-00');
        $this->resetTokenExpiresAt = new \DateTime('0000-00-00');
    }


    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles.
     *
     * @param array|null $roles
     *
     * @return Contact
     */
    public function setRoles($roles = null)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function eraseCredentials()
    {
    }
    public function getSalt()
    {
        return null;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->codco,
            $this->username,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->codco,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Get codco.
     *
     * @return int
     */
    public function getCodco()
    {
        return $this->codco;
    }

    /**
     * Set codb.
     *
     * @param int|null $codb
     *
     * @return Contact
     */
    public function setCodb($codb = null)
    {
        $this->codb = $codb;

        return $this;
    }

    /**
     * Get codb.
     *
     * @return int|null
     */
    public function getCodb()
    {
        return $this->codb;
    }

    /**
     * Set typco.
     *
     * @param bool|null $typco
     *
     * @return Contact
     */
    public function setTypco($typco = null)
    {
        $this->typco = $typco;

        return $this;
    }

    /**
     * Get typco.
     *
     * @return bool|null
     */
    public function getTypco()
    {
        return $this->typco;
    }

    /**
     * Set divco.
     *
     * @param string|null $divco
     *
     * @return Contact
     */
    public function setDivco($divco = null)
    {
        $this->divco = $divco;

        return $this;
    }

    /**
     * Get divco.
     *
     * @return string|null
     */
    public function getDivco()
    {
        return $this->divco;
    }

    /**
     * Set selco.
     *
     * @param int|null $selco
     *
     * @return Contact
     */
    public function setSelco($selco = null)
    {
        $this->selco = $selco;

        return $this;
    }

    /**
     * Get selco.
     *
     * @return int|null
     */
    public function getSelco()
    {
        return $this->selco;
    }

    /**
     * Set ident.
     *
     * @param string|null $ident
     *
     * @return Contact
     */
    public function setIdent($ident = null)
    {
        $this->ident = $ident;

        return $this;
    }

    /**
     * Get ident.
     *
     * @return string|null
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * Set civil.
     *
     * @param string|null $civil
     *
     * @return Contact
     */
    public function setCivil($civil = null)
    {
        $this->civil = $civil;

        return $this;
    }

    /**
     * Get civil.
     *
     * @return string|null
     */
    public function getCivil()
    {
        return $this->civil;
    }

    /**
     * Set civil2.
     *
     * @param string|null $civil2
     *
     * @return Contact
     */
    public function setCivil2($civil2 = null)
    {
        $this->civil2 = $civil2;

        return $this;
    }

    /**
     * Get civil2.
     *
     * @return string|null
     */
    public function getCivil2()
    {
        return $this->civil2;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Contact
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string|null $prenom
     *
     * @return Contact
     */
    public function setPrenom($prenom = null)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return Contact
     */
    public function setAdresse($adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set cp.
     *
     * @param string|null $cp
     *
     * @return Contact
     */
    public function setCp($cp = null)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp.
     *
     * @return string|null
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville.
     *
     * @param string|null $ville
     *
     * @return Contact
     */
    public function setVille($ville = null)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville.
     *
     * @return string|null
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays.
     *
     * @param string|null $pays
     *
     * @return Contact
     */
    public function setPays($pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays.
     *
     * @return string|null
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tel.
     *
     * @param string|null $tel
     *
     * @return Contact
     */
    public function setTel($tel = null)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel.
     *
     * @return string|null
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set mobil.
     *
     * @param string|null $mobil
     *
     * @return Contact
     */
    public function setMobil($mobil = null)
    {
        $this->mobil = $mobil;

        return $this;
    }

    /**
     * Get mobil.
     *
     * @return string|null
     */
    public function getMobil()
    {
        return $this->mobil;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Contact
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set societe.
     *
     * @param string|null $societe
     *
     * @return Contact
     */
    public function setSociete($societe = null)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe.
     *
     * @return string|null
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set profession.
     *
     * @param string|null $profession
     *
     * @return Contact
     */
    public function setProfession($profession = null)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession.
     *
     * @return string|null
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set mpco.
     *
     * @param string|null $mpco
     *
     * @return Contact
     */
    public function setMpco($mpco = null)
    {
        $this->mpco = $mpco;

        return $this;
    }

    /**
     * Get mpco.
     *
     * @return string|null
     */
    public function getMpco()
    {
        return $this->mpco;
    }

    /**
     * Set datnaiss.
     *
     * @param \DateTime|null $datnaiss
     *
     * @return Contact
     */
    public function setDatnaiss($datnaiss = null)
    {
        $this->datnaiss = $datnaiss;

        return $this;
    }

    /**
     * Get datnaiss.
     *
     * @return \DateTime|null
     */
    public function getDatnaiss()
    {
        return $this->datnaiss;
    }

    /**
     * Set zone.
     *
     * @param int|null $zone
     *
     * @return Contact
     */
    public function setZone($zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone.
     *
     * @return int|null
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set libre.
     *
     * @param string|null $libre
     *
     * @return Contact
     */
    public function setLibre($libre = null)
    {
        $this->libre = $libre;

        return $this;
    }

    /**
     * Get libre.
     *
     * @return string|null
     */
    public function getLibre()
    {
        return $this->libre;
    }

    /**
     * Set nolet.
     *
     * @param bool|null $nolet
     *
     * @return Contact
     */
    public function setNolet($nolet = null)
    {
        $this->nolet = $nolet;

        return $this;
    }

    /**
     * Get nolet.
     *
     * @return bool|null
     */
    public function getNolet()
    {
        return $this->nolet;
    }

    /**
     * Set rangco.
     *
     * @param int|null $rangco
     *
     * @return Contact
     */
    public function setRangco($rangco = null)
    {
        $this->rangco = $rangco;

        return $this;
    }

    /**
     * Get rangco.
     *
     * @return int|null
     */
    public function getRangco()
    {
        return $this->rangco;
    }

    /**
     * Set creatco.
     *
     * @param string|null $creatco
     *
     * @return Contact
     */
    public function setCreatco($creatco = null)
    {
        $this->creatco = $creatco;

        return $this;
    }

    /**
     * Get creatco.
     *
     * @return string|null
     */
    public function getCreatco()
    {
        return $this->creatco;
    }

    /**
     * Set jsco.
     *
     * @param string|null $jsco
     *
     * @return Contact
     */
    public function setJsco($jsco = null)
    {
        $this->jsco = $jsco;

        return $this;
    }

    /**
     * Get jsco.
     *
     * @return string|null
     */
    public function getJsco()
    {
        return $this->jsco;
    }

    /**
     * Set imgco.
     *
     * @param string|null $imgco
     *
     * @return Contact
     */
    public function setImgco($imgco = null)
    {
        $this->imgco = $imgco;

        return $this;
    }

    /**
     * Get imgco.
     *
     * @return string|null
     */
    public function getImgco()
    {
        return $this->imgco;
    }

    /**
     * Set memoco.
     *
     * @param string|null $memoco
     *
     * @return Contact
     */
    public function setMemoco($memoco = null)
    {
        $this->memoco = $memoco;

        return $this;
    }

    /**
     * Get memoco.
     *
     * @return string|null
     */
    public function getMemoco()
    {
        return $this->memoco;
    }

    /**
     * Set abolet.
     *
     * @param bool|null $abolet
     *
     * @return Contact
     */
    public function setAbolet($abolet = null)
    {
        $this->abolet = $abolet;

        return $this;
    }

    /**
     * Get abolet.
     *
     * @return bool|null
     */
    public function getAbolet()
    {
        return $this->abolet;
    }

    /**
     * Set particips.
     *
     * @param string|null $particips
     *
     * @return Contact
     */
    public function setParticips($particips = null)
    {
        $this->particips = $particips;

        return $this;
    }

    /**
     * Get particips.
     *
     * @return string|null
     */
    public function getParticips()
    {
        return $this->particips;
    }

    /**
     * Set catco.
     *
     * @param string|null $catco
     *
     * @return Contact
     */
    public function setCatco($catco = null)
    {
        $this->catco = $catco;

        return $this;
    }

    /**
     * Get catco.
     *
     * @return string|null
     */
    public function getCatco()
    {
        return $this->catco;
    }

    /**
     * Set catco2.
     *
     * @param string|null $catco2
     *
     * @return Contact
     */
    public function setCatco2($catco2 = null)
    {
        $this->catco2 = $catco2;

        return $this;
    }

    /**
     * Get catco2.
     *
     * @return string|null
     */
    public function getCatco2()
    {
        return $this->catco2;
    }

    /**
     * Set csp.
     *
     * @param int|null $csp
     *
     * @return Contact
     */
    public function setCsp($csp = null)
    {
        $this->csp = $csp;

        return $this;
    }

    /**
     * Get csp.
     *
     * @return int|null
     */
    public function getCsp()
    {
        return $this->csp;
    }

    /**
     * Set datdem.
     *
     * @param \DateTime|null $datdem
     *
     * @return Contact
     */
    public function setDatdem($datdem = null)
    {
        $this->datdem = $datdem;

        return $this;
    }

    /**
     * Get datdem.
     *
     * @return \DateTime|null
     */
    public function getDatdem()
    {
        return $this->datdem;
    }

    /**
     * Set infop.
     *
     * @param string|null $infop
     *
     * @return Contact
     */
    public function setInfop($infop = null)
    {
        $this->infop = $infop;

        return $this;
    }

    /**
     * Get infop.
     *
     * @return string|null
     */
    public function getInfop()
    {
        return $this->infop;
    }

    /**
     * Set infolg.
     *
     * @param string|null $infolg
     *
     * @return Contact
     */
    public function setInfolg($infolg = null)
    {
        $this->infolg = $infolg;

        return $this;
    }

    /**
     * Get infolg.
     *
     * @return string|null
     */
    public function getInfolg()
    {
        return $this->infolg;
    }

    /**
     * Set enregco.
     *
     * @param \DateTime|null $enregco
     *
     * @return Contact
     */
    public function setEnregco($enregco = null)
    {
        $this->enregco = $enregco;

        return $this;
    }

    /**
     * Get enregco.
     *
     * @return \DateTime|null
     */
    public function getEnregco()
    {
        return $this->enregco;
    }

    /**
     * Set tempco.
     *
     * @param string|null $tempco
     *
     * @return Contact
     */
    public function setTempco($tempco = null)
    {
        $this->tempco = $tempco;

        return $this;
    }

    /**
     * Get tempco.
     *
     * @return string|null
     */
    public function getTempco()
    {
        return $this->tempco;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Contact
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set region.
     *
     * @param string|null $region
     *
     * @return Contact
     */
    public function setRegion($region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set plainpassword.
     *
     * @param string|null $plainpassword
     *
     * @return Contact
     */
    public function setPlainpassword($plainpassword = null)
    {
        $this->plainpassword = $plainpassword;

        return $this;
    }

    /**
     * Get plainpassword.
     *
     * @return string|null
     */
    public function getPlainpassword()
    {
        return $this->plainpassword;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return Contact
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set letoca.
     *
     * @param bool $letoca
     *
     * @return Contact
     */
    public function setLetoca($letoca)
    {
        $this->letoca = $letoca;

        return $this;
    }

    /**
     * Get letoca.
     *
     * @return bool
     */
    public function getLetoca()
    {
        return $this->letoca;
    }

    /**
     * Set letpaper.
     *
     * @param bool $letpaper
     *
     * @return Contact
     */
    public function setLetpaper($letpaper)
    {
        $this->letpaper = $letpaper;

        return $this;
    }

    /**
     * Get letpaper.
     *
     * @return bool
     */
    public function getLetpaper()
    {
        return $this->letpaper;
    }

    /**
     * Set letmail.
     *
     * @param bool $letmail
     *
     * @return Contact
     */
    public function setLetmail($letmail)
    {
        $this->letmail = $letmail;

        return $this;
    }

    /**
     * Get letmail.
     *
     * @return bool
     */
    public function getLetmail()
    {
        return $this->letmail;
    }

    /**
     * Set aut16.
     *
     * @param bool $aut16
     *
     * @return Contact
     */
    public function setAut16($aut16)
    {
        $this->aut16 = $aut16;

        return $this;
    }

    /**
     * Get aut16.
     *
     * @return bool
     */
    public function getAut16()
    {
        return $this->aut16;
    }

    /**
     * Set datletoca.
     *
     * @param \DateTime|null $datletoca
     *
     * @return Contact
     */
    public function setDatletoca($datletoca = null)
    {
        $this->datletoca = $datletoca;

        return $this;
    }

    /**
     * Get datletoca.
     *
     * @return \DateTime|null
     */
    public function getDatletoca()
    {
        return $this->datletoca;
    }

    /**
     * Set datletpaper.
     *
     * @param \DateTime|null $datletpaper
     *
     * @return Contact
     */
    public function setDatletpaper($datletpaper = null)
    {
        $this->datletpaper = $datletpaper;

        return $this;
    }

    /**
     * Get datletpaper.
     *
     * @return \DateTime|null
     */
    public function getDatletpaper()
    {
        return $this->datletpaper;
    }

    /**
     * Set datletmail.
     *
     * @param \DateTime|null $datletmail
     *
     * @return Contact
     */
    public function setDatletmail($datletmail = null)
    {
        $this->datletmail = $datletmail;

        return $this;
    }

    /**
     * Get datletmail.
     *
     * @return \DateTime|null
     */
    public function getDatletmail()
    {
        return $this->datletmail;
    }

    /**
     * Set dataut16.
     *
     * @param \DateTime|null $dataut16
     *
     * @return Contact
     */
    public function setDataut16($dataut16 = null)
    {
        $this->dataut16 = $dataut16;

        return $this;
    }

    /**
     * Get dataut16.
     *
     * @return \DateTime|null
     */
    public function getDataut16()
    {
        return $this->dataut16;
    }

    /**
     * Set resetToken.
     *
     * @param string|null $resetToken
     *
     * @return Contact
     */
    public function setResetToken($resetToken = null)
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * Get resetToken.
     *
     * @return string|null
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set resetTokenExpiresAt.
     *
     * @param \DateTime|null $resetTokenExpiresAt
     *
     * @return Contact
     */
    public function setResetTokenExpiresAt($resetTokenExpiresAt = null)
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;

        return $this;
    }

    /**
     * Get resetTokenExpiresAt.
     *
     * @return \DateTime|null
     */
    public function getResetTokenExpiresAt()
    {
        return $this->resetTokenExpiresAt;
    }

    /**
     * Set newFich.
     *
     * @param bool $newFich
     *
     * @return Contact
     */
    public function setNewFich($newFich)
    {
        $this->newFich = $newFich;

        return $this;
    }

    /**
     * Get newFich.
     *
     * @return bool
     */
    public function getNewFich()
    {
        return $this->newFich;
    }
}
