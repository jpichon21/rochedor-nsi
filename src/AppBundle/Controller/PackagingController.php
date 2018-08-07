<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Packaging;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Packaging controller.
 *
 * @Route("crud/packaging")
 */
class PackagingController extends Controller
{
    /**
     * Lists all packaging entities.
     *
     * @Route("/", name="admin_packaging_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $packagings = $em->getRepository('AppBundle:Packaging')->findAll();

        return $this->render('packaging/index.html.twig', array(
            'packagings' => $packagings,
        ));
    }

    /**
     * Creates a new packaging entity.
     *
     * @Route("/new", name="admin_packaging_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $packaging = new Packaging();
        $form = $this->createForm('AppBundle\Form\PackagingType', $packaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($packaging);
            $em->flush();

            return $this->redirectToRoute('admin_packaging_show', array('id' => $packaging->getId()));
        }

        return $this->render('packaging/new.html.twig', array(
            'packaging' => $packaging,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a packaging entity.
     *
     * @Route("/{id}", name="admin_packaging_show")
     * @Method("GET")
     */
    public function showAction(Packaging $packaging)
    {
        $deleteForm = $this->createDeleteForm($packaging);

        return $this->render('packaging/show.html.twig', array(
            'packaging' => $packaging,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing packaging entity.
     *
     * @Route("/{id}/edit", name="admin_packaging_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Packaging $packaging)
    {
        $deleteForm = $this->createDeleteForm($packaging);
        $editForm = $this->createForm('AppBundle\Form\PackagingType', $packaging);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_packaging_edit', array('id' => $packaging->getId()));
        }

        return $this->render('packaging/edit.html.twig', array(
            'packaging' => $packaging,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a packaging entity.
     *
     * @Route("/{id}", name="admin_packaging_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Packaging $packaging)
    {
        $form = $this->createDeleteForm($packaging);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($packaging);
            $em->flush();
        }

        return $this->redirectToRoute('admin_packaging_index');
    }

    /**
     * Creates a form to delete a packaging entity.
     *
     * @param packaging $packaging The packaging entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Packaging $packaging)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_packaging_delete', array('id' => $packaging->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
