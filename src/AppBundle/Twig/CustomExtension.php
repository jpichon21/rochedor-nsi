<?php
namespace AppBundle\Twig;
 
use \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;
use \AppBundle\Service\PageService;
 
class CustomExtension extends \Twig_Extension
{
    /**
     * @var RouteProvider
     */
    private $routeProvider;
   
    /**
     * @var PageService
     */
    private $pageService;
    public function __construct(RouteProvider $routeProvider, PageService $pageService)
    {
        $this->routeProvider = $routeProvider;
        $this->pageService = $pageService;
    }
 
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('background', array($this, 'backgroundFilter')),
            new \Twig_SimpleFilter('constantBackground', array($this, 'constantBackgroundFilter')),
            new \Twig_SimpleFilter('md5', array($this, 'md5Filter')),
        );
    }
 
    public function getFunctions()
    {
        return array(
            new \Twig_Function('routeExists', array($this, 'routeExists')),
            new \Twig_Function('localePath', array($this, 'localePath')),
            new \Twig_Function('dynamicCanonicalLink', array($this, 'dynamicCanonicalLink'))
        );
    }
 
    public function backgroundFilter($url)
    {
        return "background-image: url('$url')";
    }
 
    public function constantBackgroundFilter($id)
    {
        $url = constant('AppBundle\\Entity\\Page::BACKGROUNDS')[$id];
        return "background-image: url('$url')";
    }
 
    public function md5Filter($string)
    {
        return md5($string);
    }
 
    public function routeExists($routeName)
    {
        return $this->routeProvider->getRoutesByNames([$routeName]);
    }
 
    public function localePath($routeName, $locale)
    {
        if ($route = $this->routeExists($routeName)[0]) {
            $availableLocales = $this->pageService->getAvailableLocales($this->pageService->getContent($routeName));
            foreach ($availableLocales as $path) {
                if (in_array($locale, explode('/', $path))) {
                    return $path;
                }
            }
            if ($locale === 'fr') {
                return $routeName;
            }
        } 
        return '#';
    }
 
    public function dynamicCanonicalLink($host, $page) 
    {   
        return 'https://'.$host.$this->pageService->getUrl($page);
    }

    public function getName()
    {
        return 'custom_extension';
    }
}