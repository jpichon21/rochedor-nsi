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

        $em->persist($page);
        $em->flush();

        return $page;
    }
    
    /**
     * @Rest\Get("/pages")
     * @Rest\View()
     */
    public function listAction()
    {
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findAll();
        if (empty($pages)) {
            return new JsonResponse("Page not found", Response::HTTP_NOT_FOUND);
        } else {
            return $pages;
        }
    }

    /**
     * @Rest\Get("/pages/{id}")
     * @Rest\View()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        if (empty($page)) {
            return new JsonResponse("Page not found", Response::HTTP_NOT_FOUND);
        } else {
            return $page;
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
            return new JsonResponse("Page not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($page);
            $em->flush();
        }
        return new JsonResponse("deleted successfully", Response::HTTP_OK);
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
}
