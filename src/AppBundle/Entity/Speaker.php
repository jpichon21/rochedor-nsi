<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Speaker
 *
 * @ORM\Table(name="speaker")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeakerRepository")
 * @Gedmo\Loggable
 */
class Speaker
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Title", type="json_array")
     */
    private $title;

    /**
     * @var array
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Description", type="json_array")
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Image", type="string", length=255)
     */
    private $image;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Speaker
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param array $title
     *
     * @return Speaker
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param array $description
     *
     * @return Speaker
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Speaker
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }
}
