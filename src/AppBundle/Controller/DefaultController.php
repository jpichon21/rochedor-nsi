<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\ServiceShowPage;

class DefaultController extends Controller
{
     /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('admin/index.html.twig');
    }

    public function showPageAction($contentDocument)
    {
        return $this->render('default/page.html.twig', array('page' => $contentDocument));
    }
}
