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

class NewsController extends Controller
{
     /**
     * @Rest\Post("/news")
     * @Rest\View()
     * @ParamConverter("news", converter="fos_rest.request_body")
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
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($news);
        if (empty($news)) {
            return new JsonResponse(['message' => 'News not found'], Response::HTTP_NOT_FOUND);
        } else {
            $news->setIntro($intro);
            $news->setDescription($description);
            $news->setUrl($url);
            $news->setStart(new \DateTime($start));
            $news->setStop(new \DateTime($stop));
            $em->persist($news);
            $em->flush();
            return new JsonResponse(['message' => 'News Updated'], Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Get("news/{id}/versions")
     * @Rest\View()
     *
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
