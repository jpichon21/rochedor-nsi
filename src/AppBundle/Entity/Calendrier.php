<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calendrier
 *
 * @ORM\Table(name="calendrier", indexes={@ORM\Index(name="CodB", columns={"CodB"})})
 * @ORM\Entity
 */
class Calendrier
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCal", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcal;

    /**
     * @var string
     *
     * @ORM\Column(name="TypCal", type="string", length=6, nullable=false)
     */
    private $typcal;

    /**
     * @var string
     *
     * @ORM\Column(name="DivCal", type="string", length=6, nullable=false)
     */
    private $divcal;

    /**
     * @var int
     *
     * @ORM\Column(name="CodB", type="integer", nullable=false)
     */
    private $codb;

    /**
     * @var int
     *
     * @ORM\Column(name="Quota", type="smallint", nullable=false)
     */
    private $quota;

    /**
     * @var string
     *
     * @ORM\Column(name="Langue", type="string", length=5, nullable=false)
     */
    private $langue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatDeb", type="datetime", nullable=false)
     */
    private $datdeb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatFin", type="datetime", nullable=false)
     */
    private $datfin;

    /**
     * @var bool
     *
     * @ORM\Column(name="Clot", type="boolean", nullable=false)
     */
    private $clot;

    /**
     * @var string
     *
     * @ORM\Column(name="InfoCal", type="text", length=65535, nullable=false)
     */
    private $infocal;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoCal", type="text", length=65535, nullable=false)
     */
    private $memocal;

    /**
     * @var string
     *
     * @ORM\Column(name="RowCal", type="text", length=65535, nullable=false)
     */
    private $rowcal;
}
