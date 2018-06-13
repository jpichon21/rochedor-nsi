<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\ServiceUpload;
use AppBundle\Entity\Media;

class FileController extends Controller
{
    /**
     * @Route("/api/file/upload")
     * @Method({"POST"})
     * @SWG\Post(
     *   path="/file/upload",
     *   summary="Add a new media",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="file",
     *                  type="object"
     *              )
     *          )
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description="The created file"
     *   )
     * )
     */
    public function postAction(Request $request, ServiceUpload $upload)
    {
        $em = $this->getDoctrine()->getManager();
        $media = new Media;
        $file = $request->files->get('file');
        if (empty($file)) {
            return new JsonResponse(['message' => 'File not found'], Response::HTTP_NOT_FOUND);
        }
        $path = $upload->assignName($file);
        $check = $this->getDoctrine()->getRepository('AppBundle:Media')->findByPath($path);
        if ($this->getDoctrine()->getRepository('AppBundle:Media')->findByPath('/uploads/'.$path)) {
            return new JsonResponse(['message' => 'File Exists'], Response::HTTP_FORBIDDEN);
        }
        $media->setPath('/uploads/'.$path);
        $media->setCreatedAt(new \Datetime);
        $media->setUpdatedAt(new \Datetime);
        $em->persist($media);
        if ($upload->upload($file)) {
            $em->flush();
        }
        return $this->json($media);
        return new JsonResponse($media, Response::HTTP_OK);
    }

     /**
     * @Route("/api/file/{id}")
     * @Method({"GET"})
     * @SWG\Get(
     *  path="/file/{id}",
     *      summary="Get requested media",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The media id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested media"
     *      )
     *    )
     */
    public function showAction($id)
    {
        $media = $this->getDoctrine()->getRepository('AppBundle:Media')->findById($id);

        return $this->json($media);
    }


    /**
     * @Route("/api/file/")
     * @Method({"GET"})
     * @SWG\Get(
     *  path="/file",
     *      summary="Get list of media",
     *      @SWG\Response(
     *        response=200,
     *        description="The medias'list"
     *      )
     *    )
     */
    public function listAction()
    {
        $media = $this->getDoctrine()->getRepository('AppBundle:Media')->findAll();

        return $this->json($media);
    }
}
