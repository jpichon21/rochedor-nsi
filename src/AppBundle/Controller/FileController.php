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

class FileController extends Controller
{
    /**
     * @Route("/api/file/upload")
     * @Method({"POST"})
     */
    public function postAction(Request $request, ServiceUpload $upload)
    {
        $file = $request->files->get('file');
        if (empty($file)) {
            return new JsonResponse("File not found", Response::HTTP_NOT_FOUND);
        } else {
            $upload->upload($file);
            return new JsonResponse("File Upload", Response::HTTP_OK);
        }
    }
}
