<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Speaker;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Gedmo\Loggable;

class SpeakerController extends Controller
{
     /**
     * @Rest\Post("/speakers")
     * @Rest\View()
     * @ParamConverter("speaker", converter="fos_rest.request_body")
     */
    public function postAction(Speaker $speaker)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($speaker);
        $em->flush();

        return $speaker;
    }

    /**
     * @Rest\Get("/speakers")
     * @Rest\View()
     */
    public function listAction()
    {
        $speakers = $this->getDoctrine()->getRepository('AppBundle:Speaker')->findAll();
        return $speakers;
    }

    /**
     * @Rest\Get("/speakers/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     */
    public function showAction($id, $version)
    {
        if ($version === null) {
            $em = $this->getDoctrine()->getManager();
            $speaker = $em->getRepository('AppBundle:Speaker')->findOneById($id);
            return $speaker;
        } else {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
            $speaker = $em->getRepository('AppBundle:Speaker')->findOneById($id);
            $logs = $repo->getLogEntries($speaker);
            $countLogs = count($logs) - 1;
            $firstLog = $logs[$countLogs];
            for ($i = ($countLogs); $i >= 0; $i--) {
                if ($logs[$i]->getVersion() <= $version) {
                    $diff = array_diff_key($firstLog->getData(), $logs[$i]->getData());
                    $oldSpeaker = array_merge($diff, $logs[$i]->getData());
                }
            }
            return $oldSpeaker;
        }
    }

    /**
     * @Rest\Delete("/speakers/{id}")
     * @Rest\View()
     */
    public function deleteAction($id)
    {
        $data = new Speaker;
        $em = $this->getDoctrine()->getManager();
        $speaker = $this->getDoctrine()->getRepository('AppBundle:Speaker')->find($id);
        if (empty($speaker)) {
            return new JsonResponse("Speaker not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($speaker);
            $em->flush();
        }
        return new JsonResponse("deleted successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/speakers/{id}")
     * @Rest\View()
     */
    public function putAction($id, Request $request)
    {

        $data = new Speaker;
        $name = $request->get('name');
        $title = $request->get('title');
        $description = $request->get('description');
        $image = $request->get('image');
        $em = $this->getDoctrine()->getManager();
        $speaker = $em->find('AppBundle\Entity\Speaker', $id);
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($speaker);
        if (empty($speaker)) {
            return new JsonResponse(['message' => 'Speaker not found'], Response::HTTP_NOT_FOUND);
        } else {
            $speaker->setName($name);
            $speaker->setTitle($title);
            $speaker->setDescription($description);
            $speaker->setImage($image);

            $em->persist($speaker);
            $em->flush();
            return new JsonResponse(['message' => 'Speaker Updated'], Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Put("/speakers/{id}/{version}", requirements={"version"="\d+"})
     * @Rest\View()
     */
    public function revertAction($id, $version)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $speaker = $em->getRepository('AppBundle:Speaker')->findOneById($id);
        $logs = $repo->getLogEntries($speaker);
        $repo->revert($speaker, $version);
        $em->persist($speaker);
        $em->flush();
        return $speaker;
    }
}
