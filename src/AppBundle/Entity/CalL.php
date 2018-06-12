<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalL
 *
 * @ORM\Table(
 *  name="cal_l",
 *  indexes={
 *  @ORM\Index(name="CodCo", * columns={"LCal"}),
 *  @ORM\Index(name="CodCal", columns={"CodCal"}), @ORM\Index(name="hebLCal", columns={"hebLCal"})
 *  }
 * )
 * @ORM\Entity
 */
class CalL
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCalL", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcall;

    /**
     * @var int
     *
     * @ORM\Column(name="CodCal", type="integer", nullable=false)
     */
    private $codcal;

    /**
     * @var int
     *
     * @ORM\Column(name="LCal", type="integer", nullable=false)
     */
    private $lcal;

    /**
     * @var string
     *
     * @ORM\Column(name="TypLCal", type="string", length=6, nullable=false)
     */
    private $typlcal;

    /**
     * @var string
     *
     * @ORM\Column(name="RefLCal", type="string", length=20, nullable=false)
     */
    private $reflcal;

    /**
     * @var string
     *
     * @ORM\Column(name="EtapLCal", type="string", length=6, nullable=false)
     */
    private $etaplcal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RepLCal", type="date", nullable=false)
     */
    private $replcal;

    /**
     * @var int
     *
     * @ORM\Column(name="hebLCal", type="integer", nullable=false)
     */
    private $heblcal;

    /**
     * @var string
     *
     * @ORM\Column(name="ChLCal", type="string", length=6, nullable=false)
     */
    private $chlcal;

    /**
     * @var bool
     *
     * @ORM\Column(name="SaisieLCal", type="boolean", nullable=false)
     */
    private $saisielcal;

    /**
     * @var string
     *
     * @ORM\Column(name="CreatLCal", type="string", length=15, nullable=false)
     */
    private $creatlcal;

    /**
     * @var int
     *
     * @ORM\Column(name="TriLCal", type="smallint", nullable=false)
     */
    private $trilcal;

    /**
     * @var bool
     *
     * @ORM\Column(name="SelLCal", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sellcal = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="JSLCal", type="text", length=65535, nullable=false)
     */
    private $jslcal;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoLCal", type="text", length=65535, nullable=false)
     */
    private $memolcal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EnregLCal", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $enreglcal = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="OldIns", type="integer", nullable=false)
     */
    private $oldins;
}
