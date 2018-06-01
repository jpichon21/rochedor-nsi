<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use JMS\Serializer\Annotation\Type;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Page
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="subTitle", type="string", length=255)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @Gedmo\Versioned
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
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, nullable=true)
     */
    private $locale;

    /**
     * @var AppBundle/Entity/Page
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     */
    private $parent;

    /**
     * @var AppBundle/Entity/Page
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    /**
     * @var RouteObjectInterface[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     * targetEntity="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route",
     * cascade={"persist", "remove"})
     */
    private $routes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string
     * @Type("string")
     */
    private $url;

    /**
     * @var int
     * @Type("int")
     */
    private $parentId;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

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

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Page $parent
     *
     * @return Page
     */
    public function setParent(\AppBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return Page
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return RouteObjectInterface[]|ArrayCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param RouteObjectInterface[]|ArrayCollection $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param RouteObjectInterface $route
     *
     * @return $this
     */
    public function addRoute($route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * @param RouteObjectInterface $route
     *
     * @return $this
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);

        return $this;
    }

    /**
     * Add child
     *
     * @param Page $child
     *
     * @return Page
     */
    public function addChild(Page $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Page $child
     */
    public function removeChild(Page $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateModified()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Page
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get parentId
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Get tempUrl
     *
     * @return string
     */
    public function getTempUrl()
    {
        return $this->url;
    }
    
    /**
     * Set tempUrl
     *
     * @return $this
     */
    public function setTempUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
