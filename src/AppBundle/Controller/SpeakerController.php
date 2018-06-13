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
use Swagger\Annotations as SWG;

class SpeakerController extends Controller
{
     /**
     * @Rest\Post("/speaker")
     * @Rest\View()
     * @ParamConverter("speaker", converter="fos_rest.request_body")
     * @SWG\Post(
    *   path="/speaker",
    *   summary="Add a new speaker",
    *   @SWG\Parameter(
    *          name="body",
    *          in="body",
    *          required=true,
    *          @SWG\Schema(
    *              @SWG\Property(
    *                  property="name",
    *                  type="string"
    *              ),
    *              @SWG\Property(
    *                  property="title",
    *                  type="object"
    *              ),
    *              @SWG\Property(
    *                  property="description",
    *                  type="object"
    *              ),
    *              @SWG\Property(
    *                  property="image",
    *                  type="string"
    *              ),
    *              @SWG\Property(
    *                  property="position",
    *                  type="integer"
    *              ),
    *          )
    *     ),
    *   @SWG\Response(
    *     response=200,
    *     description="The created speaker"
    *   )
    * )
     */
    public function postAction(Speaker $speaker)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($speaker);
        $em->flush();

        return $speaker;
    }

    /**
     * @Rest\Get("/speaker")
     * @Rest\View()
     * @SWG\Get(
     *  path="/speaker",
     *      summary="Get requested speakers'list ordered by position",
     *      @SWG\Response(
     *        response=200,
     *        description="The requested speakers"
     *      )
     *    )
     */
    public function listAction()
    {
        $speakers = $this->getDoctrine()->getRepository('AppBundle:Speaker')->findAllOrderByPos();
        return $speakers;
    }

    /**
     * @Rest\Get("/speaker/{id}/{version}", requirements={"version"="\d+"} , defaults={"version" = null})
     * @Rest\View()
     * @SWG\Get(
     *  path="/speaker/{id}/{version}",
     *      summary="Get a speaker",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The speaker id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="version",
     *          in="path",
     *          description="The speaker version",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested speaker"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Speaker not found"
     *      ),
     *    )
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
            $oldSpeaker['id'] = $id;
            return $oldSpeaker;
        }
    }

    /**
     * @Rest\Delete("/speaker/{id}")
     * @Rest\View()
     * @SWG\Delete(
     *  path="/speaker/id",
     *      summary="Delete requested speaker",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="speaker id",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The speaker is deleted"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Speaker not found"
     *      )
     *    )
     */
    public function deleteAction($id)
    {
        $data = new Speaker;
        $em = $this->getDoctrine()->getManager();
        $speaker = $this->getDoctrine()->getRepository('AppBundle:Speaker')->find($id);
        if (empty($speaker)) {
            return new JsonResponse(['message' => 'Speaker not found'], Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($speaker);
            $em->flush();
        }
        return new JsonResponse(['message' => 'speaker deleted'], Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/speaker/{id}")
     * @Rest\View()
     * @SWG\Put(
     *   path="/speaker/{id}",
     *   summary="Edit requester speaker",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="name",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="title",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="image",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="position",
     *                  type="integer"
     *              ),
     *          )
     *     ),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="speaker id",
     *          required=true,
     *          type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="Speaker not found"
     *   )
     * )
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
        if (empty($speaker)) {
            return new JsonResponse(['message' => 'Speaker not found'], Response::HTTP_NOT_FOUND);
        }
        $gedmo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $logs = $gedmo->getLogEntries($speaker);
        $speaker->setName($name);
        $speaker->setTitle($title);
        $speaker->setDescription($description);
        $speaker->setImage($image);

        $em->persist($speaker);
        $em->flush();
        return new JsonResponse(['message' => 'Speaker Updated'], Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/speaker/{id}/versions")
     * @Rest\View()
     * @SWG\Get(
     *  path="/speaker/{id}/versions",
     *      summary="Return all log entries for the selected speaker",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The speaker ID",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested logs"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="Speaker not found"
     *      )
     *    )
     * @param integer $id
     * @return json
     */
    public function getVersionsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $speaker = $em->getRepository('AppBundle:Speaker')->findOneById($id);
        if (empty($speaker)) {
            return new JsonResponse(['message' => 'Speaker not found'], Response::HTTP_NOT_FOUND);
        }
        $logs = $repo->getLogEntries($speaker);
        return $logs;
    }

    /**
     * @Rest\Put("/speaker/{id}/{version}", requirements={"version"="\d+"})
     * @Rest\View()
     * @SWG\Put(
     *  path="/speaker/{id}/versions",
     *      summary="Revert a speaker into the selected version",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The speaker id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="version",
     *          in="path",
     *          description="The speaker version",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested speaker at the selected version"
     *      ),
     *    )
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
    
    /**
     * @Rest\Put("/speaker/{id}/position/{position}", requirements={"position"="\d+"})
     * @Rest\View()
     * @SWG\Put(
     *  path="/speaker/{id}/position/{position}",
     *      summary="Change the position of a speaker and return a list with the new scheduling",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The speaker id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="position",
     *          in="path",
     *          description="The speaker desired position",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="A speakers'list with the new scheduling"
     *      ),
     *    )
     */
    public function setPositionAction($id, $position)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle\Entity\Speaker');
        $speaker = $em->getRepository('AppBundle:Speaker')->findOneById($id);
        $speaker->setPosition($position);
        $em->persist($speaker);
        $em->flush();
        $speakerOrdered = $em->getRepository('AppBundle:Speaker')->findAllOrderByPos();
        return $speakerOrdered;
    }
}
