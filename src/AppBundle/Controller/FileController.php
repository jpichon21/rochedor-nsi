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
     */
    public function postAction(Request $request, ServiceUpload $upload)
    {
        $em = $this->getDoctrine()->getManager();
        $media = new Media;
        $file = $request->files->get('file');
        if (empty($file)) {
            return new JsonResponse("File not found", Response::HTTP_NOT_FOUND);
        }
        $path = $upload->assignName($file);
        $check = $this->getDoctrine()->getRepository('AppBundle:Media')->findByPath($path);
        if ($this->getDoctrine()->getRepository('AppBundle:Media')->findByPath("../web/uploads/".$path) != null) {
            return new JsonResponse("File Exists", Response::HTTP_FORBIDDEN);
        }
        $media->setPath("../web/uploads/".$path);
        $media->setCreatedAt(new \Datetime);
        $media->setUpdatedAt(new \Datetime);
        $em->persist($media);
        if ($upload->upload($file)) {
            $em->flush();
        }
        // return $media;
        return $this->json($media);
        return new JsonResponse($media, Response::HTTP_OK);
    }

     /**
     * @Route("/api/file/{id}")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        $media = $this->getDoctrine()->getRepository('AppBundle:Media')->findById($id);

        return $this->json($media);
    }


    /**
     * @Route("/api/file/")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $media = $this->getDoctrine()->getRepository('AppBundle:Media')->findAll();

        return $this->json($media);
    }
}
