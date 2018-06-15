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

    /**
     * Get codcol.
     *
     * @return int
     */
    public function getCodcol()
    {
        return $this->codcol;
    }

    /**
     * Set col.
     *
     * @param int $col
     *
     * @return ContactL
     */
    public function setCol($col)
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get col.
     *
     * @return int
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * Set colp.
     *
     * @param int $colp
     *
     * @return ContactL
     */
    public function setColp($colp)
    {
        $this->colp = $colp;

        return $this;
    }

    /**
     * Get colp.
     *
     * @return int
     */
    public function getColp()
    {
        return $this->colp;
    }

    /**
     * Set colt.
     *
     * @param string $colt
     *
     * @return ContactL
     */
    public function setColt($colt)
    {
        $this->colt = $colt;

        return $this;
    }

    /**
     * Get colt.
     *
     * @return string
     */
    public function getColt()
    {
        return $this->colt;
    }

    /**
     * Set colrel.
     *
     * @param int $colrel
     *
     * @return ContactL
     */
    public function setColrel($colrel)
    {
        $this->colrel = $colrel;

        return $this;
    }

    /**
     * Get colrel.
     *
     * @return int
     */
    public function getColrel()
    {
        return $this->colrel;
    }

    /**
     * Set coltyp.
     *
     * @param string $coltyp
     *
     * @return ContactL
     */
    public function setColtyp($coltyp)
    {
        $this->coltyp = $coltyp;

        return $this;
    }

    /**
     * Get coltyp.
     *
     * @return string
     */
    public function getColtyp()
    {
        return $this->coltyp;
    }

    /**
     * Set jscol.
     *
     * @param string $jscol
     *
     * @return ContactL
     */
    public function setJscol($jscol)
    {
        $this->jscol = $jscol;

        return $this;
    }

    /**
     * Get jscol.
     *
     * @return string
     */
    public function getJscol()
    {
        return $this->jscol;
    }
}
