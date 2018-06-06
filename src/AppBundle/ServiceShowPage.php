<?php
namespace AppBundle;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as CmfRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Controller;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;

class ServiceShowPage
{
    private $contentRepository;
    private $routeProvider;

    public function __construct(ContentRepository $contentRepository, RouteProvider $routeProvider)
    {
        $this->contentRepository = $contentRepository;
        $this->routeProvider = $routeProvider;
    }

    public function getMyContent($route)
    {
        $contentDocuments =  $this->routeProvider->getRoutesByNames([$route]);
        if (!$contentDocuments) {
            return null;
        }
        $id = $contentDocuments[0]->getDefaults();
        $page = $this->contentRepository->findById($id['_content_id']);
        return $page;
    }
}
