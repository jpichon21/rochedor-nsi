<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Page;
use \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    /**
     *
     * @Route("/sitemap.xml", name="sitemap")
     * @Method("GET")
    */
    public function sitemapAction(Request $request)
    {
        $urls = [];
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findAll();
        
        foreach ($pages as $page) {
            $urls[] = ['loc' => $page->getRoutes()->getValues()[0]->getStaticPrefix()];
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]);
    }
}
