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

class PageController extends Controller
{
    /**
     * @Rest\Post("/pages")
     * @Rest\View()
     * @ParamConverter("page", converter="fos_rest.request_body")
     */
    public function postAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = null;
        if ($page->getParentId() != null) {
            $id = $page->getParentId();
            $parent = $this->getDoctrine()->getRepository('AppBundle:Page')->find($id);
            $page->setParent($parent);
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

        $contentRepository = $this->container->get('cmf_routing.content_repository');
        $routeProvider = $this->container->get('cmf_routing.route_provider');
        
        $route = new CmfRoute();

        if (!$page->getUrl()) {
            $routeName = $this->slugify($page->getTitle());
        } else {
            $routeName =  $this->slugify($page->getUrl());
        }
        if ($routeProvider->getRoutesByNames([$routeName])) {
            return new JsonResponse(['message' => 'Route already exists'], Response::HTTP_FORBIDDEN);
        }

        $route->setName($routeName);
        $route->setStaticPrefix('/' . $route->getName());
        $route->setDefault(RouteObjectInterface::CONTENT_ID, $contentRepository->getContentId($page));
        $route->setContent($page);
        $page->addRoute($route);

        $em->persist($page);
        $em->flush();

        return $page;
    }
    
    /**
     * @Rest\Get("/pages")
     * @Rest\View()
     */
    public function listAction(Request $request)
    {
        $locale = ($request->query->has('locale')) ?
            $request->query->get('locale') :
            $this->container->getParameter('locale');
        
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findByLocale($locale);
        return $pages;
    }

    /**
     * @Rest\Get("/pages/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     */
    public function showAction($id, $version)
    {
        if ($version === null) {
            $em = $this->getDoctrine()->getManager();
            $page = $em->getRepository('AppBundle:Page')->findOneById($id);
            if ($page === null) {
                return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
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
            return $oldPage;
        }
    }

    /**
     * @Rest\Delete("/pages/{id}")
     * @Rest\View()
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
     */
    public function putAction($id, Request $request)
    {

        $data = new Page;
        $title = $request->get('title');
        $subTitle = $request->get('sub_title');
        $description = $request->get('description');
        $content = $request->get('content');
        $bg = $request->get('background');
        $em = $this->getDoctrine()->getManager();
        $page = $em->find('AppBundle\Entity\Page', $id);
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($page);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        } else {
            $page->setTitle($title);
            $page->setSubTitle($subTitle);
            $page->setDescription($description);
            $page->setContent($content);
            $page->setBackground($bg);
            $em->persist($page);
            $em->flush();
            return new JsonResponse(['message' => 'Page Updated'], Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Put("/pages/{id}/{version}", requirements={"version"="\d+"})
     * @Rest\View()
     */
    public function revertAction($id, $version)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        $logs = $repo->getLogEntries($page);
        $repo->revert($page, $version);
        $em->persist($page);
        $em->flush();
    }

    /**
     * @Rest\Get("pages/{id}/children")
     * @Rest\View()
     *
     * @param integer $id
     * @return json
     */
    public function getChildrenAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
        return $page->getChildren();
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
