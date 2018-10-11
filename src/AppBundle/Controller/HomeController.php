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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Gedmo\Loggable;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route as CmfRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use AppBundle\Service\PageService;
use Swagger\Annotations as SWG;

class HomeController extends Controller
{
    
    /**
    * @Rest\Get("/home/{locale}/{version}",
    * requirements={"version"="\d+", "locale"="[a-z]{2}"} , defaults={"version" = null})")
    * @Security("has_role('ROLE_ADMIN_HOME_VIEW')")
    * @SWG\Get(
    *  path="/home/{locale}/{version}",
    *      summary="Get requested home",
    *      @SWG\Parameter(
    *          name="locale",
    *          in="path",
    *          description="Locale home",
    *          required=true,
    *          type="string"
    *      ),
    *      @SWG\Parameter(
    *          name="version",
    *          in="path",
    *          description="The home version",
    *          required=true,
    *          type="integer"
    *      ),
    *      @SWG\Response(
    *        response=200,
    *        description="The requested home"
    *      ),
    *      @SWG\Response(
    *        response=404,
    *        description="Home not found"
    *      ),
    *    )
    * @Rest\View()
    */
    public function showAction($locale, $version, PageService $pageService)
    {
        $page = $pageService->getContent($locale);
        if (!$page) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
        
        if ($version === null) {
            if ($page->getRoutes()) {
                $page->setTempUrl($page->getRoutes()[0]->getName());
            }
            return $page;
        }
        
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
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
    
    
    /**
    * @Rest\Put("/home/{id}", requirements={"id"="\d+"})
    * @Security("has_role('ROLE_ADMIN_HOME_EDIT')")
    * @SWG\Put(
    *   path="/home/{id}",
    *   summary="Edit requested home",
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
    *          description="The home id",
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
        $url = $request->get('url');
        $locale = $request->get('locale');
        $em = $this->getDoctrine()->getManager();
        $page = $em->find('AppBundle\Entity\Page', $id);
        if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($page);
        $page->setTitle($title);
        $page->setSubTitle($subTitle);
        $page->setDescription($description);
        $page->setContent($content);
        $page->setBackground($bg);
        $page->setLocale($locale);
            
        $em->persist($page);
        $em->flush();
            
        return $page;
    }
    
    /**
    * @Rest\Get("home/{id}/versions", requirements={"id"="\d+"})
    * @Security("has_role('ROLE_ADMIN_HOME_VIEW')")
    * @Rest\View()
    * @SWG\Get(
    *  path="/home/{id}/versions",
    *      summary="Get requested home's versions",
    *      @SWG\Parameter(
    *          name="id",
    *          in="path",
    *          description="The home id",
    *          required=true,
    *          type="integer"
    *      ),
    *      @SWG\Response(
    *        response=200,
    *        description="The requested home's versions"
    *      ),
    *      @SWG\Response(
    *        response=404,
    *        description="Home not found"
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
