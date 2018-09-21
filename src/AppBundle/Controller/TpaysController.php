<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tpays;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\TpaysRepository;

/**
 * Tpays controller.
 *
 * @Route("crud/tpays")
 */
class TpaysController extends Controller
{
    /**
     * Lists all tpay entities.
     *
     * @Route("/", name="crud_tpays_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tpays = $em->getRepository('AppBundle:Tpays')->findAll();

        return $this->render('tpays/index.html.twig', array(
            'tpays' => $tpays,
        ));
    }

    /**
     * Creates a new tpay entity.
     *
     * @Route("/new", name="crud_tpays_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tpay = new Tpay();
        $form = $this->createForm('AppBundle\Form\TpaysType', $tpay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tpay);
            $em->flush();

            return $this->redirectToRoute('crud_tpays_show', array('codpays' => $tpay->getCodpays()));
        }

        return $this->render('tpays/new.html.twig', array(
            'tpay' => $tpay,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tpay entity.
     *
     * @Route("/{codpays}", name="crud_tpays_show")
     * @Method("GET")
     */
    public function showAction(Tpays $tpay)
    {
        $deleteForm = $this->createDeleteForm($tpay);

        return $this->render('tpays/show.html.twig', array(
            'tpay' => $tpay,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tpay entity.
     *
     * @Route("/{codpays}/edit", name="crud_tpays_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tpays $tpay)
    {
        $deleteForm = $this->createDeleteForm($tpay);
        $editForm = $this->createForm('AppBundle\Form\TpaysType', $tpay);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('crud_tpays_edit', array('codpays' => $tpay->getCodpays()));
        }

        return $this->render('tpays/edit.html.twig', array(
            'tpay' => $tpay,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tpay entity.
     *
     * @Route("/{codpays}", name="crud_tpays_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tpays $tpay)
    {
        $form = $this->createDeleteForm($tpay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tpay);
            $em->flush();
        }

        return $this->redirectToRoute('crud_tpays_index');
    }

    /**
     * Creates a form to delete a tpay entity.
     *
     * @param Tpays $tpay The tpay entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tpays $tpay)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_tpays_delete', array('codpays' => $tpay->getCodpays())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * @Rest\Get("/xhr/tpays/code/{country}", name="get_country_code")
     * @Rest\View()
    */
    public function xhrGetCountryCode(Request $request, $country, TpaysRepository $repo)
    {
        return ['status' => 'ok' , 'data' => $repo->findCode($country)];
    }
}
