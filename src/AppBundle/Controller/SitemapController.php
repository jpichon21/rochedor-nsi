<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Page;
use \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\RouteProvider;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function sitemapAction(Request $request) 
    {
        $urls = [];
    
        $hostname = $request->getHost();
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findAll();
        
        foreach ($pages as $page){
            $urls[] = ['loc' => $page->getRoutes()->getValues()[0]->getStaticPrefix(), 'changefreq' => 'weekly', 'priority' => '1.0']; 
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    }
}