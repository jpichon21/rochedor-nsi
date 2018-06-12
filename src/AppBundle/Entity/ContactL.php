<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactL
 *
 * @ORM\Table(name="contact_l",
 *  indexes={@ORM\Index(name="CodCo",
 *  columns={"Col", "ColP"}),
 *  @ORM\Index(name="Col", columns={"Col"}),
 *  @ORM\Index(name="ColP", columns={"ColP"})}
 * )
 * @ORM\Entity
 */
class ContactL
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodCol", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codcol;

    /**
     * @var int
     *
     * @ORM\Column(name="Col", type="integer", nullable=false)
     */
    private $col;

    /**
     * @var int
     *
     * @ORM\Column(name="ColP", type="integer", nullable=false)
     */
    private $colp;

    /**
     * @var string
     *
     * @ORM\Column(name="ColT", type="string", length=6, nullable=false)
     */
    private $colt;

    /**
     * @var int
     *
     * @ORM\Column(name="ColRel", type="integer", nullable=false)
     */
    private $colrel;

    /**
     * @var string
     *
     * @ORM\Column(name="ColTyp", type="string", length=6, nullable=false)
     */
    private $coltyp;

    /**
     * @var string
     *
     * @ORM\Column(name="JSCol", type="text", length=65535, nullable=false)
     */
    private $jscol;
}
