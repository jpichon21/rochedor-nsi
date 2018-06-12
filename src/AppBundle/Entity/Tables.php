<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tables
 *
 * @ORM\Table(name="tables")
 * @ORM\Entity
 */
class Tables
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idt;

    /**
     * @var int
     *
     * @ORM\Column(name="TLien", type="integer", nullable=false)
     */
    private $tlien;

    /**
     * @var string
     *
     * @ORM\Column(
     *  name="TTyp",
     *  type="string",
     *  length=6,
     *  nullable=false,
     *  options={"comment"="Vide=Standard; LANG=Liaison traduction"}
     * )
     */
    private $ttyp;

    /**
     * @var string
     *
     * @ORM\Column(name="TRef", type="string", length=6, nullable=false)
     */
    private $tref;

    /**
     * @var string
     *
     * @ORM\Column(name="TLib", type="string", length=80, nullable=false)
     */
    private $tlib;

    /**
     * @var int
     *
     * @ORM\Column(name="TNiv", type="smallint", nullable=false)
     */
    private $tniv;

    /**
     * @var string
     *
     * @ORM\Column(name="TPath", type="string", length=40, nullable=false)
     */
    private $tpath;

    /**
     * @var int
     *
     * @ORM\Column(name="TRang", type="smallint", nullable=false)
     */
    private $trang;

    /**
     * @var string
     *
     * @ORM\Column(name="TLang", type="string", length=3, nullable=false)
     */
    private $tlang;

    /**
     * @var string
     *
     * @ORM\Column(name="TMemo", type="text", length=65535, nullable=false)
     */
    private $tmemo;
}
