<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Gedmo\Mapping\Annotation as Gedmo;
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
        0 => '/assets/img/bg-fallback.jpg',
        1 => '/assets/img/bg-1.jpg',
        2 => '/assets/img/bg-2.jpg',
        3 => '/assets/img/bg-3.jpg',
        4 => '/assets/img/bg-4.jpg',
        5 => '/assets/img/bg-5.jpg',
        6 => '/assets/img/bg-6.jpg',
        7 => '/assets/img/bg-7.jpg'
    ];

    const TYPE_PAGE = 'page';
    const TYPE_EDITIONS = 'editions';
    const TYPE_ASSOCIATION = 'association';
    const TYPE_MINIMAL = 'minimal';

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
     * @ORM\Column(name="Title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="SubTitle", type="string", length=100)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="Content", type="json_array", nullable=true)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="Background", type="integer", nullable=true)
     */
    private $background;

    /**
     * @var string
     *
     * @ORM\Column(name="Locale", type="string", length=10, nullable=true)
     */
    private $locale;

    /**
     * @var AppBundle/Entity/Page
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children", cascade={"persist", "remove"})
     */
    private $parent;

    /**
     * @var AppBundle/Entity/Page[]\ArrayCollection
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist", "remove"})
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
     * @ORM\Column(name="Maj", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var string
     * @Type("string")
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(name="Immutableid", type="string", length=100)
     */
    private $immutableid;

    /**
     * @var int
     * @Type("int")
     */
    private $parentId;

    /**
     * @var string
     * @ORM\Column(name="Type", type="string", length=20)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="Category", type="string", length=255)
     */
    private $category;

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

    /**
     * Get immutableid
     *
     * @return string
     */
    public function getImmutableid()
    {
        return $this->immutableid;
    }
    
    /**
     * Set immutableid
     *
     * @return $this
     */
    public function setImmutableid($immutableid)
    {
        $this->immutableid = $immutableid;
        return $this;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Page
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
