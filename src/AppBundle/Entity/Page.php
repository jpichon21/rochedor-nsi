<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PageRepository")
 * @Gedmo\Loggable
 */
class Page implements Translatable
{
    const BACKGROUNDS = [
        0 => 'web/assets/img/bg-fallback.jpg',
        1 => 'web/assets/img/bg-1.jpg',
        2 => 'web/assets/img/bg-2.jpg',
        3 => 'web/assets/img/bg-3.jpg',
        4 => 'web/assets/img/bg-4.jpg',
        5 => 'web/assets/img/bg-5.jpg',
        6 => 'web/assets/img/bg-6.jpg'
    ];
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Translatable
     * @ORM\Column(name="subTitle", type="string", length=255)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @Gedmo\Versioned
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="json_array", nullable=true)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="background", type="integer", nullable=true)
     */
    private $background;


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
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Page
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param array $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set background
     *
     * @param integer $background
     *
     * @return Page
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return int
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Set subTitle
     *
     * @param string $subTitle
     *
     * @return Page
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * Get subTitle
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }
}
