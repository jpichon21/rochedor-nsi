<?php
namespace AppBundle\Service;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as CmfRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Controller;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PageService
{
    private $contentRepository;
    private $routeProvider;
    private $locales;
    private $urlGenerator;

    public function __construct(
        ContentRepository $contentRepository,
        RouteProvider $routeProvider,
        String $locales,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->contentRepository = $contentRepository;
        $this->routeProvider = $routeProvider;
        $this->locales = explode('|', $locales);
        $this->urlGenerator = $urlGenerator;
    }

    public function getContent($route)
    {
        $contentDocuments =  $this->routeProvider->getRoutesByNames([$route]);
        if (!$contentDocuments) {
            return null;
        }
        $id = $contentDocuments[0]->getDefaults();
        $page = $this->contentRepository->findById($id['_content_id']);
        return $page;
    }

    public function getAvailableLocales($contentDocument)
    {
        if ($contentDocument === null) {
            return null;
        }
        $contentDocument->setLocale('fr');
        $availableLocales = array();
     
        if ($contentDocument->getLocale() === "fr") {
            $cm = $contentDocument->getChildren();
            $myChild = $cm->getValues();
        } else {
            $cm = $contentDocument->getParent();
            $mc = $cm->getChildren();
            $myChild = $mc->getValues();
            $tmpP = $cm->getRoutes()->getValues();
            if (!$tmpP) {
                return null;
            }
            $availableLocales['fr'] = $tmpP[0]->getStaticPrefix();
        }
        foreach ($myChild as $childPage) {
            $childPage->setLocale('fr');
            if ($childPage->getLocale() != $contentDocument->getLocale()) {
                $key = $childPage->getLocale();
                $tmp = $childPage->getRoutes()->getValues();
                if ($tmp) {
                    $availableLocales[$key] = $tmp[0]->getStaticPrefix();
                }
            }
        }
        return $availableLocales;
    }

    public function getContentFromRequest(Request $request)
    {
        $route = $this->guessRouteName($request->getPathInfo());
        return $this->getContent($route);
    }

    private function guessRouteName($path)
    {
        $pieces = explode('/', $path);
        return end($pieces);
    }
}
