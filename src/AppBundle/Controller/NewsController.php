<?php

namespace AppBundle\Controller;

use AppBundle\Entity\News;
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

class NewsController extends Controller
{
    /**
     * @Rest\Post("/news")
     * @Rest\View()
     * @ParamConverter("news", converter="fos_rest.request_body")
     * @SWG\Post(
     *   path="/news",
     *   summary="Add a news",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="intro",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="url",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="start",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="stop",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="locale",
     *                  type="string"
     *              )
     *          )
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description="The created news"
     *   )
     * )
     */
    public function postAction(News $news)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($news);
        $em->flush();

        return $news;
    }

    /**
     * @Rest\Get("/news")
     * @Rest\View()
     * @SWG\Get(
     *  path="/news",
     *      summary="Get requested locale news' list",
     *      @SWG\Parameter(
     *          name="locale",
     *          in="query",
     *          description="The requested locale",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested news"
     *      )
     *    )
     */
    public function listAction(Request $request)
    {
        $locale = ($request->query->has('locale')) ?
            $request->query->get('locale') :
            $this->container->getParameter('locale');
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->findNextByLocale($locale);
        return $news;
    }

    /**
     * @Rest\Get("/news/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     * @SWG\Get(
     *  path="/news/{id}/{version}",
     *      summary="Get requested news",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The news id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="version",
     *          in="path",
     *          description="The news version",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested news"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="News not found"
     *      ),
     *    )
     */
    public function showAction($id, $version)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')->findOneById($id);
        if ($news === null) {
            return new JsonResponse(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }
        if ($version === null) {
            return $news;
        }
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $logs = $repo->getLogEntries($news);
        $countLogs = count($logs) - 1;
        $firstLog = $logs[$countLogs];
        for ($i = ($countLogs); $i >= 0; $i--) {
            if ($logs[$i]->getVersion() <= $version) {
                $diff = array_diff_key($firstLog->getData(), $logs[$i]->getData());
                $oldNews = array_merge($diff, $logs[$i]->getData());
            }
        }
        $news->setIntro($oldNews['intro']);
        $news->setDescription($oldNews['description']);
        $news->setUrl($oldNews['url']);
        $news->setStart($oldNews['start']);
        $news->setStop($oldNews['stop']);
        return $news;
    }

    /**
     * @Rest\Delete("/news/{id}")
     * @Rest\View()
     * @SWG\Delete(
     *  path="/news/id",
     *      summary="Delete requested news",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="news id",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description=""
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="news not found"
     *      )
     *    )
     */
    public function deleteAction($id)
    {
        $data = new News;
        $em = $this->getDoctrine()->getManager();
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->find($id);
        if (empty($news)) {
            return new JsonResponse(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($news);
            $em->flush();
        }
        return new JsonResponse(['message' => 'Deleted successfully'], Response::HTTP_OK);
    }


    /**
     * @Rest\Put("/news/{id}")
     * @Rest\View()
     * @SWG\Put(
     *   path="/news/{id}",
     *   summary="Edit requested news",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="intro",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="url",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="start",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="stop",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="locale",
     *                  type="string"
     *              )
     *          )
     *     ),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The requested news' id",
     *          required=true,
     *          type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="News not found"
     *   )
     * )
     */
    public function putAction($id, Request $request)
    {

        $data = new News;
        $intro = $request->get('intro');
        $description = $request->get('description');
        $url = $request->get('url');
        $start = $request->get('start');
        $stop = $request->get('stop');
        $em = $this->getDoctrine()->getManager();
        $news = $em->find('AppBundle\Entity\News', $id);
        if (empty($news)) {
            return new JsonResponse(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($news);
        $news->setIntro($intro);
        $news->setDescription($description);
        $news->setUrl($url);
        $news->setStart(new \DateTime($start));
        $news->setStop(new \DateTime($stop));
        $em->persist($news);
        $em->flush();
        return new JsonResponse(['message' => 'News Updated'], Response::HTTP_OK);
    }

    /**
     * @Rest\Get("news/{id}/versions")
     * @Rest\View()
     * @SWG\Get(
     *  path="/news/{id}/versions",
     *      summary="Get requested news' versionss",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The news'id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested news'versions"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="News not found"
     *      )
     *    )
     * @param integer $id
     * @return json
     */
    public function getVersionsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $news = $em->getRepository('AppBundle:News')->findOneById($id);
        if (empty($news)) {
            return new JsonResponse(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        }
        $logs = $repo->getLogEntries($news);
        return $logs;
    }
}
