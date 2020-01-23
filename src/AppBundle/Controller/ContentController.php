<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gedmo\Loggable;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as CmfRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Swagger\Annotations as SWG;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;

/**
 *
 * @SWG\Swagger(
 *   schemes={"https"},
 *   host="rochedor.fr",
 *   basePath="/api"
 * )
 * @SWG\Info(
 * title="Content API documentation",
 * version="1.0.0"
 * )
 */
class ContentController extends Controller
{
    private $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }
    
    
    private function checkImmutability($immutableid)
    {
        $em = $this->getDoctrine()->getManager();
        if ($em->getRepository('AppBundle:Page')->findOneByImmutableid($immutableid)) {
            return true;
        }
    }
    /**
     * @Rest\Get("/content")
     * @Rest\View()
     * @Security("is_granted('ROLE_ADMIN_CONTENT_VIEW')")
     * @SWG\Get(
     *  path="/content",
     *      summary="Get requested locale content' list",
     *      @SWG\Parameter(
     *          name="locale",
     *          in="query",
     *          description="The requested locale",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested pages"
     *      )
     *    )
     */
    public function listAction(Request $request)
    {
        $locale = ($request->query->has('locale')) ?
            $request->query->get('locale') :
            $this->container->getParameter('locale');
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findByLocale($locale);

        // remove homepage
        foreach ($pages as $key => $page) {
            if (count($page->getRoutes()) > 0) {
                if ($page->getRoutes()[0]->getName() === $page->getLocale()) {
                    array_splice($pages, $key, 1);
                }
            }
        }

        // remove pages based on rights
        if (! $this->isGranted('ROLE_SUPER_ADMIN')) {
            $pages = array_filter($pages, function (Page $page) {
                return strlen($page->getType()) && $page->getType() !== Page::TYPE_ADMIN;
            });
        }
        if (! $this->isGranted('ROLE_ADMIN_CONTENT_ASSOCIATION_VIEW')) {
            $pages = array_filter($pages, function (Page $page) {
                return strlen($page->getType()) && $page->getType() !== Page::TYPE_ASSOCIATION;
            });
        }
        if (! $this->isGranted('ROLE_ADMIN_CONTENT_EDITION_VIEW')) {
            $pages = array_filter($pages, function (Page $page) {
                return $page->getType() !== Page::TYPE_EDITIONS;
            });
        }

        return array_values($pages);
    }
  
    /**
     * @Rest\Get("/content/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     * @Security("has_role('ROLE_ADMIN_CONTENT_VIEW')")
     * @SWG\Get(
     *  path="/content/{id}/{version}",
     *      summary="Get a page",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The page ID",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="version",
     *          in="path",
     *          description="The page version",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested page"
     *      )
     *    )
     */
    public function showAction($id, $version)
    {
        if ($version === null) {
            $em = $this->getDoctrine()->getManager();
            /** @var Page $page */
            $page = $em->getRepository('AppBundle:Page')->findOneById($id);
            if ($page === null) {
                return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
            }
            if (count($page->getRoutes()) > 0) {
                $page->setTempUrl($page->getRoutes()[0]->getName());
            }
            return $page;
        } else {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
            /** @var Page $page */
            $page = $em->getRepository('AppBundle:Page')->findOneById($id);
            $logs = $repo->getLogEntries($page);
            $countLogs = count($logs) - 1;
            $firstLog = $logs[$countLogs];
            for ($i = ($countLogs); $i >= 0; $i--) {
                if ($logs[$i]->getVersion() <= $version) {
                    if ($logs[$i]->getData()) {
                        $diff = array_diff_key($firstLog->getData(), $logs[$i]->getData());
                        $oldPage = array_merge($diff, $logs[$i]->getData());
                    }
                }
            }
            if (isset($oldPage['title'])) {
                $page->setTitle($oldPage['title']);
            }
            if (isset($oldPage['subTitle'])) {
                $page->setSubTitle($oldPage['subTitle']);
            }
            if (isset($oldPage['description'])) {
                $page->setDescription($oldPage['description']);
            }
            if (isset($oldPage['content'])) {
                $page->setContent($oldPage['content']);
            }
            if ($page->getRoutes()) {
                $page->setTempUrl($page->getRoutes()[0]->getName());
            }
            return $page;
        }
    }

    /**
     * @Rest\Put("/content/{id}")
     * @Rest\View()
     * @Security("has_role('ROLE_ADMIN_CONTENT_EDIT')")
     * @SWG\Put(
     *   path="/content/id",
     *   summary="Edit requested page",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="title",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="sub_title",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="content",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="locale",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="url",
     *                  type="string"
     *              )
     *          )
     *     ),
     *   @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          type="integer"
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="Content not found"
     *   )
     * )
     */
    public function putAction($id, Request $request)
    {
        $title = $request->get('title');
        $subTitle = $request->get('sub_title');
        $description = $request->get('description');
        $content = $request->get('content');
        $bg = $request->get('background');
        $url = $request->get('url');
        $locale = $request->get('locale');
        $em = $this->getDoctrine()->getManager();
        /** @var Page $page */
        $page = $em->find('AppBundle\Entity\Page', $id);

        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        } else {
            $page->setTitle($title);
            $page->setSubTitle($subTitle);
            $page->setDescription($description);
            $page->setContent($content);
            $page->setBackground($bg);
            $page->setLocale($locale);

            $oldUrl = null;
            if ($page->getRoutes()) {
                $oldUrl = isset($page->getRoutes()[0]) ? $page->getRoutes()[0]->getName() : null;
            }

            if ($oldUrl !== $url) {
                $routeProvider = $this->container->get('cmf_routing.route_provider');
                if ($routeProvider->getRoutesByNames([$url])) {
                    return new JsonResponse(['message' => 'Route already exists'], Response::HTTP_FORBIDDEN);
                }
    
                $routes = $page->getRoutes();
                foreach ($routes as $key => $route) {
                    $route->setName($url);
                    $route->setStaticPrefix('/' . $url);
                    $routes[$key] = $route;
                }
            }

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                $page->setCategory($request->get('category'));
                $page->setType($request->get('type'));
            }
            
            $em->persist($page);
            $em->flush();

            return new JsonResponse(['message' => 'Page updated']);
        }
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($page);
        $page->setTitle($title);
        $page->setSubTitle($subTitle);
        $page->setDescription($description);
        $page->setContent($content);
        $page->setBackground($bg);
        $em->persist($page);
        $em->flush();
        return new JsonResponse(['message' => 'Page Updated'], Response::HTTP_OK);
    }

    /**
     * @Rest\Get("content/{id}/translations")
     * @Rest\View()
     * @Security("has_role('ROLE_ADMIN_CONTENT_VIEW')")
     * @SWG\Get(
     *  path="/content/{id}/translations",
     *      summary="Get requested page's translatations",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="the page id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description=""
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Page not found"
     *      )
     *    )
     * @param integer $id
     * @return json
     */
    public function getTranslationsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
        return ($page->getParent() === null) ? $page->getChildren() : $page->getParent()->getChildren();
    }

    /**
     * @Rest\Get("content/{id}/versions")
     * @Rest\View()
     * @Security("has_role('ROLE_ADMIN_CONTENT_VIEW')")
     * @SWG\Get(
     *  path="/content/{id}/versions",
     *      summary="Return all log entries for the selected page",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="the page ID",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested logs of your selected page"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Page not found"
     *      )
     *    )
     * @param integer $id
     * @return json
     */
    public function getVersionsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
        $logs = $repo->getLogEntries($page);
        return $logs;
    }
}
