<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\ServiceShowPage;
use AppBundle\Controller\PageController;
use AppBundle\Entity\Page;

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
        if ($contentDocument->getLocale() === "fr") {
            $cm = $contentDocument->getChildren();
            $myChild = $cm->getValues();
        } else {
            $cm = $contentDocument->getParent();
            $mc = $cm->getChildren();
            $myChild = $mc->getValues();
        }
        $availableLocal = array();
        foreach ($myChild as $childPage) {
            $key = $childPage->getLocale();
            $tmp = $childPage->getRoutes()->getValues();
            $availableLocal[$key] = $tmp[0]->getStaticPrefix();
        }

        return $this->render('default/page.html.twig', array(
            'page' => $contentDocument,
            'availableLocal' => $availableLocal
        ));
    }
}
