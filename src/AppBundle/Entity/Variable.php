<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Variable
 *
 * @ORM\Table(name="variable")
 * @ORM\Entity
 */
class Variable
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodVar", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codvar;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=40, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Ident", type="string", length=15, nullable=false)
     */
    private $ident;

    /**
     * @var int
     *
     * @ORM\Column(name="CleN", type="integer", nullable=false)
     */
    private $clen;

    /**
     * @var string
     *
     * @ORM\Column(name="Typ", type="string", length=8, nullable=false)
     */
    private $typ;

    /**
     * @var string
     *
     * @ORM\Column(name="Typ2", type="string", length=8, nullable=false)
     */
    private $typ2;

    /**
     * @var string
     *
     * @ORM\Column(name="ValeurT", type="string", length=80, nullable=false)
     */
    private $valeurt;

    /**
     * @var int
     *
     * @ORM\Column(name="ValeurN", type="integer", nullable=false)
     */
    private $valeurn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ValeurD", type="datetime", nullable=false)
     */
    private $valeurd;

    /**
     * @var bool
     *
     * @ORM\Column(name="ValeurB", type="boolean", nullable=false)
     */
    private $valeurb;

    /**
     * @var string
     *
     * @ORM\Column(name="ValeurM", type="text", length=65535, nullable=false)
     */
    private $valeurm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatMaj", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datmaj = 'CURRENT_TIMESTAMP';

    public function setValeurn($valeurn)
    {
        $this->valeurn = $valeurn;
        return $this;
    }
}
