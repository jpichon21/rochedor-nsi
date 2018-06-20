<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gedmo\Loggable;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as CmfRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Swagger\Annotations as SWG;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;

/**
 *
 * @SWG\Swagger(
 *   schemes={"https"},
 *   host="rochedor.fr",
 *   basePath="/api"
 * )
 * @SWG\Info(
 * title="Page API documentation",
 * version="1.0.0"
 * )
 */
class PageController extends Controller
{
    private $contentRepository;
    private $routeProvider;

    public function __construct(ContentRepository $contentRepository, RouteProvider $routeProvider)
    {
        $this->contentRepository = $contentRepository;
        $this->routeProvider = $routeProvider;
    }
    /**
    * @Rest\Post("/pages")
    * @Rest\View()
    * @ParamConverter("page", converter="fos_rest.request_body")
    * @SWG\Post(
    *   path="/pages",
    *   summary="Add a new page",
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
    *   @SWG\Response(
    *     response=200,
    *     description="The created page"
    *   )
    * )
    */
    public function postAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = null;
        if ($page->getParentId() != null) {
            $id = $page->getParentId();
            $parent = $this->getDoctrine()->getRepository('AppBundle:Page')->find($id);
            $page->setParent($parent);
            $page->setImmutableid($this->slugify($parent->getImmutableid()));
        } else {
            if ($this->checkImmutability($this->slugify($page->getTitle() . $page->getSubTitle()))) {
                return new JsonResponse(['message' => 'immutable already-exist'], Response::HTTP_FORBIDDEN);
            } else {
                $page->setImmutableid($this->slugify($page->getTitle() . $page->getSubTitle()));
            }
        }
        $em->persist($page);

        if ($page->getLocale() !== "fr" && $parent === null) {
            return new JsonResponse(['message' => 'Wrong Argument, parent is null'], Response::HTTP_FORBIDDEN);
        }
        if ($page->getLocale() !== "fr" && $parent !== null) {
            if ($parent->getLocale() !== 'fr') {
                return new JsonResponse(['message' => 'Wrong Argument, parent is not fr'], Response::HTTP_FORBIDDEN);
            }
        }
        if ($page->getLocale() === 'fr' && $parent !== null) {
            return new JsonResponse(
                ['message' => 'Wrong Argument, Your parent must be null'],
                Response::HTTP_FORBIDDEN
            );
        }
        $em->flush();
        
        $route = new CmfRoute();

        if (!$page->getUrl()) {
            $routeName = $this->slugify($page->getTitle() . $page->getSubTitle());
        } else {
            $routeName =  $this->slugify($page->getUrl());
        }
        if ($this->routeProvider->getRoutesByNames([$routeName])) {
            return new JsonResponse(['message' => 'Route already exists'], Response::HTTP_FORBIDDEN);
        }

        $route->setName($routeName);
        $route->setStaticPrefix('/' . $route->getName());
        
        $route->setDefault(RouteObjectInterface::CONTENT_ID, $this->contentRepository->getContentId($page));
        $route->setDefault('_locale', $page->getLocale());
        $route->setContent($page);
        $em->persist($route);
        $page->addRoute($route);
        $em->persist($page);
        $em->flush();

        return $page;
    }
    
    private function checkImmutability($immutableid)
    {
        $em = $this->getDoctrine()->getManager();
        if ($em->getRepository('AppBundle:Page')->findOneByImmutableid($immutableid)) {
            return true;
        }
    }
    /**
     * @Rest\Get("/pages")
     * @Rest\View()
     * @SWG\Get(
     *  path="/pages",
     *      summary="Get requested locale pages' list",
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
        foreach ($pages as $key => $page) {
            if (count($page->getRoutes()) > 0) {
                if ($page->getRoutes()[0]->getName() === $page->getLocale()) {
                    array_splice($pages, $key, 1);
                }
            }
        }
        return $pages;
    }
  
    /**
     * @Rest\Get("/pages/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     * @SWG\Get(
     *  path="/pages/{id}/{version}",
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
            $page = $em->getRepository('AppBundle:Page')->findOneById($id);
            $logs = $repo->getLogEntries($page);
            $countLogs = count($logs) - 1;
            $firstLog = $logs[$countLogs];
            for ($i = ($countLogs); $i >= 0; $i--) {
                if ($logs[$i]->getVersion() <= $version) {
                    $diff = array_diff_key($firstLog->getData(), $logs[$i]->getData());
                    $oldPage = array_merge($diff, $logs[$i]->getData());
                }
            }
            $page->setTitle($oldPage['title']);
            $page->setSubTitle($oldPage['subTitle']);
            $page->setDescription($oldPage['description']);
            $page->setContent($oldPage['content']);
            if ($page->getRoutes()) {
                $page->setTempUrl($page->getRoutes()[0]->getName());
            }
            return $page;
        }
    }

    /**
     * @Rest\Delete("/pages/{id}")
     * @Rest\View()
     * @SWG\Delete(
     *  path="/pages/id",
     *      summary="Delete requested page",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The page id",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The page is deleted"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Page not found"
     *      )
     *    )
     */
    public function deleteAction($id)
    {
        $data = new Page;
        $em = $this->getDoctrine()->getManager();
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->find($id);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($page);
            $em->flush();
        }
        return new JsonResponse(['message' => 'Deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/pages/{id}")
     * @Rest\View()
     * @SWG\Put(
     *   path="/pages/id",
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
     *     description="Page not found"
     *   )
     * )
     */
    public function putAction($id, Request $request)
    {

        $data = new Page;
        $title = $request->get('title');
        $subTitle = $request->get('sub_title');
        $description = $request->get('description');
        $content = $request->get('content');
        $bg = $request->get('background');
        $url = $request->get('url');
        $locale = $request->get('locale');
        $em = $this->getDoctrine()->getManager();
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
                $oldUrl = $page->getRoutes()[0]->getName();
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
     * @Rest\Get("pages/{id}/translations")
     * @Rest\View()
     * @SWG\Get(
     *  path="/pages/{id}/translations",
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
     * @Rest\Get("pages/{id}/versions")
     * @Rest\View()
     * @SWG\Get(
     *  path="/pages/{id}/versions",
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

    /**
    * Return slugified string
    *
    * @param string $string
    * @param array $replace
    * @param string $delimiter
    * @return string Slugified string
    */
    private function slugify($string, $replace = array(), $delimiter = '-')
    {
        if (!extension_loaded('iconv')) {
            throw new Exception('iconv module not loaded');
        }
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
            $clean = str_replace((array) $replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        return $clean;
    }
}
