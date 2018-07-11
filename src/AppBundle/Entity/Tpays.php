<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tpays
 *
 * @ORM\Table(name="tpays")
 * @ORM\Entity
 */
class Tpays
{
    /**
     * @var string
     *
     * @ORM\Column(name="NomPays", type="string", length=30, nullable=false)
     */
    private $nompays;

    /**
     * @var string
     *
     * @ORM\Column(name="CodPays", type="string", length=2)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codpays;

    public function getNompays()
    {
        return $this->nompays;
    }

    public function getCodpays()
    {
        return $this->codpays;
    }
}
