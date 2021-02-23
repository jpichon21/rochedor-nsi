<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prodrub
 *
 * @ORM\Table(name="prodrub")
 * @ORM\Entity
 */
class Prodrub
{
    /**
     * @var int
     *
     * @ORM\Column(name="CodRub", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codrub;

    /**
     * @var string
     *
     * @ORM\Column(name="Rubrique", type="string", length=60, nullable=false)
     */
    private $rubrique;

    /**
     * @var string
     *
     * @ORM\Column(name="ImgRub", type="text", length=65535, nullable=false)
     */
    private $imgrub;

    /**
     * @var string
     *
     * @ORM\Column(name="LangRub", type="string", length=2, nullable=false)
     */
    private $langrub;

    /**
     * @var bool
     *
     * @ORM\Column(name="rubHide", type="boolean", nullable=false)
     */
    private $rubhide;

    /**
     * @var string
     *
     * @ORM\Column(name="MemoRub", type="text", length=65535, nullable=false)
     */
    private $memorub;

    public function getCodrub()
    {
        return $this->codrub;
    }

    public function getRubrique()
    {
        return $this->rubrique;
    }

    public function getTyprub()
    {
        return $this->typrub;
    }
}
