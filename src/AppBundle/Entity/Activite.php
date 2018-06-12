<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activite
 *
 * @ORM\Table(name="activite", indexes={@ORM\Index(name="CoAct", columns={"Referent"})})
 * @ORM\Entity
 */
class Activite
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodAct", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codact;

    /**
     * @var string
     *
     * @ORM\Column(name="LibAct", type="string", length=150, nullable=false)
     */
    private $libact;

    /**
     * @var string
     *
     * @ORM\Column(name="Recur", type="string", length=6, nullable=false)
     */
    private $recur;

    /**
     * @var string
     *
     * @ORM\Column(name="SitAct", type="string", length=6, nullable=false)
     */
    private $sitact;

    /**
     * @var string
     *
     * @ORM\Column(name="DivAct", type="string", length=6, nullable=false)
     */
    private $divact;

    /**
     * @var string
     *
     * @ORM\Column(name="TypAct", type="string", length=6, nullable=false)
     */
    private $typact;

    /**
     * @var string
     *
     * @ORM\Column(name="Referent", type="string", length=20, nullable=false)
     */
    private $referent;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoAct", type="text", length=65535, nullable=false)
     */
    private $memoact;

    /**
     * @var string
     *
     * @ORM\Column(name="InfoAct", type="text", length=65535, nullable=false)
     */
    private $infoact;

    /**
     * @var string
     *
     * @ORM\Column(name="CodOld", type="string", length=10, nullable=false)
     */
    private $codold;
}
