<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Shipping;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Shipping controller.
 *
 * @Route("crud/shipping")
 */
class ShippingController extends Controller
{
    /**
     * Lists all shipping entities.
     *
     * @Route("/", name="admin_shipping_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shippings = $em->getRepository('AppBundle:Shipping')->findAll();

        return $this->render('shipping/index.html.twig', array(
            'shippings' => $shippings,
        ));
    }

    /**
     * Creates a new shipping entity.
     *
     * @Route("/new", name="admin_shipping_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shipping = new Shipping();
        $form = $this->createForm('AppBundle\Form\ShippingType', $shipping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shipping);
            $em->flush();

            return $this->redirectToRoute('admin_shipping_show', array('id' => $shipping->getId()));
        }

        return $this->render('shipping/new.html.twig', array(
            'shipping' => $shipping,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shipping entity.
     *
     * @Route("/{id}", name="admin_shipping_show")
     * @Method("GET")
     */
    public function showAction(Shipping $shipping)
    {
        $deleteForm = $this->createDeleteForm($shipping);

        return $this->render('shipping/show.html.twig', array(
            'shipping' => $shipping,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shipping entity.
     *
     * @Route("/{id}/edit", name="admin_shipping_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Shipping $shipping)
    {
        $deleteForm = $this->createDeleteForm($shipping);
        $editForm = $this->createForm('AppBundle\Form\ShippingType', $shipping);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_shipping_edit', array('id' => $shipping->getId()));
        }

        return $this->render('shipping/edit.html.twig', array(
            'shipping' => $shipping,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a shipping entity.
     *
     * @Route("/{id}", name="admin_shipping_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Shipping $shipping)
    {
        $form = $this->createDeleteForm($shipping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shipping);
            $em->flush();
        }

        return $this->redirectToRoute('admin_shipping_index');
    }

    /**
     * Creates a form to delete a shipping entity.
     *
     * @param shipping $shipping The shipping entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shipping $shipping)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_shipping_delete', array('id' => $shipping->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
