<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client implements UserInterface, \Serializable
{

    /**
     * @var int
     *
     * @ORM\Column(name="CodCli", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcli;

    /**
     * @var string
     *
     * @ORM\Column(name="Civil", type="string", length=6, nullable=false)
     */
    private $civil;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=25, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="Rue", type="string", length=50, nullable=false)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="text", length=65535, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="CP", type="string", length=8, nullable=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="Ville", type="string", length=35, nullable=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="Pays", type="string", length=20, nullable=false)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel", type="string", length=20, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="Mobil", type="string", length=20, nullable=false)
     */
    private $mobil;

    /**
     * @var string
     *
     * @ORM\Column(name="eMail", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Societe", type="string", length=40, nullable=true)
     */
    private $societe;

    /**
     * @var string
     *
     * @ORM\Column(name="tvaIntra", type="string", length=255, nullable=true)
     */
    private $tvaintra;

    /**
     * @var string
     *
     * @ORM\Column(name="mpCli", type="string", length=15, nullable=true)
     * @Exclude
     */
    private $mpcli;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoCli", type="text", length=65535, nullable=false)
     */
    private $memocli;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EnregCli", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $enregcli = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Exclude
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_token", type="string", nullable=true)
     */
    private $resetToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reset_token_expires_at", type="datetime", nullable=true)
     */
    private $resetTokenExpiresAt;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true, nullable=true)
     */
    private $username;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="professionnel", type="boolean", nullable=false)
     */
    private $professionnel;

    /**
     * @var bool
     *
      * @ORM\Column(name="DateConDonnees", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateConDonnees;
    
    /**
     * @var bool
     *
      * @ORM\Column(name="DateNewsDonnees", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateNewsDonnees;

    public function getRoles()
    {
        return ['ROLE_SHOP_USER'];
    }

    /**
     * Set roles.
     *
     * @param array|null $roles
     *
     * @return Client
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
            $this->codcli,
            $this->email,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->codcli,
            $this->email,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Set civil.
     *
     * @param string $civil
     *
     * @return Client
     */
    public function setCivil($civil)
    {
        $this->civil = $civil;

        return $this;
    }

    /**
     * Get civil.
     *
     * @return string
     */
    public function getCivil()
    {
        return $this->civil;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set rue.
     *
     * @param string $rue
     *
     * @return Client
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue.
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set cp.
     *
     * @param string $cp
     *
     * @return Client
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp.
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville.
     *
     * @param string $ville
     *
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville.
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays.
     *
     * @param string $pays
     *
     * @return Client
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays.
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tel.
     *
     * @param string $tel
     *
     * @return Client
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel.
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set mobil.
     *
     * @param string $mobil
     *
     * @return Client
     */
    public function setMobil($mobil)
    {
        $this->mobil = $mobil;

        return $this;
    }

    /**
     * Get mobil.
     *
     * @return string
     */
    public function getMobil()
    {
        return $this->mobil;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set societe.
     *
     * @param string $societe
     *
     * @return Client
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe.
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set tvaintra.
     *
     * @param string $tvaintra
     *
     * @return Client
     */
    public function setTvaintra($tvaintra)
    {
        $this->tvaintra = $tvaintra;

        return $this;
    }

    /**
     * Get tvaintra.
     *
     * @return string
     */
    public function getTvaintra()
    {
        return $this->tvaintra;
    }

    /**
     * Set mpcli.
     *
     * @param string $mpcli
     *
     * @return Client
     */
    public function setMpcli($mpcli)
    {
        $this->mpcli = $mpcli;

        return $this;
    }

    /**
     * Get mpcli.
     *
     * @return string
     */
    public function getMpcli()
    {
        return $this->mpcli;
    }

    /**
     * Set memocli.
     *
     * @param string $memocli
     *
     * @return Client
     */
    public function setMemocli($memocli)
    {
        $this->memocli = $memocli;

        return $this;
    }

    /**
     * Get memocli.
     *
     * @return string
     */
    public function getMemocli()
    {
        return $this->memocli;
    }

    /**
     * Set enregcli.
     *
     * @param \DateTime $enregcli
     *
     * @return Client
     */
    public function setEnregcli($enregcli)
    {
        $this->enregcli = $enregcli;

        return $this;
    }

    /**
     * Get enregcli.
     *
     * @return \DateTime
     */
    public function getEnregcli()
    {
        return $this->enregcli;
    }
    
    /**
     * Set resetToken.
     *
     * @param string|null $resetToken
     *
     * @return Client
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
     * @return Client
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
     * Set username.
     *
     * @param string|null $username
     *
     * @return Client
     */
    public function setUsername($username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set professionnel.
     *
     * @param boolean $professionnel
     *
     * @return Client
     */
    public function setProfessionnel($professionnel)
    {
        $this->professionnel = $professionnel;

        return $this;
    }

    /**
     * Get professionnel.
     *
     * @return string
     */
    public function getProfessionnel()
    {
        return $this->professionnel;
    }

    /**
     * Set dateConDonnees.
     *
     * @param \DateTime|null $dateConDonnees
     *
     * @return Client
     */
    public function setDateConDonnees($dateConDonnees = null)
    {
        $this->dateConDonnees = $dateConDonnees;

        return $this;
    }

    /**
     * Get dateConDonnees.
     *
     * @return \DateTime|null
     */
    public function getDateConDonnees()
    {
        return $this->dateConDonnees;
    }

    /**
     * Set dateNewsDonnees.
     *
     * @param \DateTime|null $dateNewsDonnees
     *
     * @return Client
     */
    public function setDateNewsDonnees($dateNewsDonnees = null)
    {
        $this->dateNewsDonnees = $dateNewsDonnees;

        return $this;
    }

    /**
     * Get DateNewsDonnees.
     *
     * @return \DateTime|null
     */
    public function getDateNewsDonnees()
    {
        return $this->dateNewsDonnees;
    }

    public function getConData()
    {
        if ($this->dateConDonnees === null) {
            return false;
        }
        return true;
    }

    public function getConNews()
    {
        if ($this->dateNewsDonnees === null) {
            return false;
        }
        return true;
    }
}
