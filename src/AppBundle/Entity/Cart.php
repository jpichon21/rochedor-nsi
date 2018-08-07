<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Cart
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cartline", mappedBy="cart", cascade={"remove"})
    */
    private $cartlines;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cartlines = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created = new \DateTime();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setUpdatedValue()
    {
        $this->updated = new \DateTime();
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Cart
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Cart
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set notes.
     *
     * @param string|null $notes
     *
     * @return Cart
     */
    public function setNotes($notes = null)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string|null
     */
    public function getNotes()
    {
        return $this->notes;
    }
    

    /**
     * Add cartline.
     *
     * @param \AppBundle\Entity\Cartline $cartline
     *
     * @return Cart
     */
    public function addCartline(\AppBundle\Entity\Cartline $cartline)
    {
        $this->cartlines[] = $cartline;

        return $this;
    }

    /**
     * Remove cartline.
     *
     * @param \AppBundle\Entity\Cartline $cartline
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCartline(\AppBundle\Entity\Cartline $cartline)
    {
        return $this->cartlines->removeElement($cartline);
    }

    /**
     * Get cartlines.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartlines()
    {
        return $this->cartlines;
    }
}
