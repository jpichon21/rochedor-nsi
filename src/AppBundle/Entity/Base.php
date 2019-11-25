<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base
 *
 * @ORM\Table(name="base")
 * @ORM\Entity
 */
class Base
{
    /**
     * @var int
     *
     * @ORM\Column(name="Cle", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=80, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="Ref", type="string", length=30, nullable=false)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="TypDoc", type="string", length=10, nullable=false)
     */
    private $typdoc;

    /**
     * @var int
     *
     * @ORM\Column(name="Conf", type="integer", nullable=false)
     */
    private $conf;

    /**
     * @var string
     *
     * @ORM\Column(name="Etat", type="string", length=6, nullable=false)
     */
    private $etat;

    /**
     * @var bool
     *
     * @ORM\Column(name="DAdm", type="boolean", nullable=false)
     */
    private $dadm;

    /**
     * @var bool
     *
     * @ORM\Column(name="DAj", type="boolean", nullable=false)
     */
    private $daj;

    /**
     * @var bool
     *
     * @ORM\Column(name="DAff", type="boolean", nullable=false)
     */
    private $daff;

    /**
     * @var int
     *
     * @ORM\Column(name="bmem", type="integer", nullable=false)
     */
    private $bmem;

    /**
     * @var bool
     *
     * @ORM\Column(name="Hide", type="boolean", nullable=false)
     */
    private $hide;

    /**
     * @var string
     *
     * @ORM\Column(name="Target", type="string", length=12, nullable=false)
     */
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(name="Createur", type="string", length=15, nullable=false)
     */
    private $createur;

    /**
     * @var bool
     *
     * @ORM\Column(name="Archiv", type="boolean", nullable=false)
     */
    private $archiv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatDoc", type="datetime", nullable=false)
     */
    private $datdoc;

    /**
     * @var string
     *
     * @ORM\Column(name="AdrWeb", type="text", length=65535, nullable=false)
     */
    private $adrweb;

    /**
     * @var string
     *
     * @ORM\Column(name="Info", type="text", length=65535, nullable=false)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="Droit", type="text", length=65535, nullable=false)
     */
    private $droit;

    /**
     * @var string
     *
     * @ORM\Column(name="Dest", type="text", length=65535, nullable=false)
     */
    private $dest;

    /**
     * @var string
     *
     * @ORM\Column(name="Descript", type="text", length=65535, nullable=false)
     */
    private $descript;

    /**
     * @var string
     *
     * @ORM\Column(name="Enreg", type="text", length=65535, nullable=false)
     */
    private $enreg;

    /**
     * @var string
     *
     * @ORM\Column(name="Data", type="text", length=65535, nullable=false)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="Rapport", type="text", length=65535, nullable=false)
     */
    private $rapport;

    /**
     * @var bool
     *
     * @ORM\Column(name="Sel", type="boolean", nullable=false)
     */
    private $sel;

    /**
     * @var int
     *
     * @ORM\Column(name="CodCrea", type="integer", nullable=false)
     */
    private $codcrea;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatEnreg", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datenreg = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatMaj", type="datetime", nullable=false)
     */
    private $datmaj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatFin", type="date", nullable=false)
     */
    private $datfin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatObj", type="datetime", nullable=false)
     */
    private $datobj;
}
