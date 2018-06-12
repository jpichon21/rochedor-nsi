<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseL
 *
 * @ORM\Table(name="base_l", indexes={@ORM\Index(name="Cle", columns={"Cle"}), @ORM\Index(name="clp", columns={"clp"})})
 * @ORM\Entity
 */
class BaseL
{
    /**
     * @var int
     *
     * @ORM\Column(name="cl1", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cl1;

    /**
     * @var int
     *
     * @ORM\Column(name="clp", type="integer", nullable=false)
     */
    private $clp;

    /**
     * @var int
     *
     * @ORM\Column(name="cl0", type="integer", nullable=false)
     */
    private $cl0;

    /**
     * @var int
     *
     * @ORM\Column(name="clL", type="integer", nullable=false)
     */
    private $cll;

    /**
     * @var string
     *
     * @ORM\Column(name="PathNod", type="string", length=40, nullable=false)
     */
    private $pathnod;

    /**
     * @var string
     *
     * @ORM\Column(name="Chemin", type="string", length=43, nullable=false)
     */
    private $chemin;

    /**
     * @var int
     *
     * @ORM\Column(name="Cle", type="integer", nullable=false)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="TypL", type="string", length=12, nullable=false)
     */
    private $typl;

    /**
     * @var int
     *
     * @ORM\Column(name="Modul", type="integer", nullable=false)
     */
    private $modul;

    /**
     * @var int
     *
     * @ORM\Column(name="Niv", type="smallint", nullable=false)
     */
    private $niv;

    /**
     * @var int
     *
     * @ORM\Column(name="Nivs", type="integer", nullable=false)
     */
    private $nivs;

    /**
     * @var bool
     *
     * @ORM\Column(name="Ouv", type="boolean", nullable=false)
     */
    private $ouv;

    /**
     * @var int
     *
     * @ORM\Column(name="Rang", type="integer", nullable=false, options={"default"="999"})
     */
    private $rang = '999';

    /**
     * @var int
     *
     * @ORM\Column(name="OptLien", type="integer", nullable=false)
     */
    private $optlien;

    /**
     * @var string
     *
     * @ORM\Column(name="TypLien", type="string", length=7, nullable=false)
     */
    private $typlien;

    /**
     * @var string
     *
     * @ORM\Column(name="InfoLien", type="text", length=65535, nullable=false)
     */
    private $infolien;
}
