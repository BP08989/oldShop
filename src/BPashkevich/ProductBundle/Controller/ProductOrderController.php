<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\ProductOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Productorder controller.
 *
 */
class ProductOrderController extends Controller
{
    /**
     * Lists all productOrder entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productOrders = $em->getRepository('BPashkevichProductBundle:ProductOrder')->findAll();

        return $this->render('productorder/index.html.twig', array(
            'productOrders' => $productOrders,
        ));
    }

    /**
     * Creates a new productOrder entity.
     *
     */
    public function newAction(Request $request)
    {
        $productOrder = new Productorder();
        $form = $this->createForm('BPashkevich\ProductBundle\Form\ProductOrderType', $productOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productOrder);
            $em->flush();

            return $this->redirectToRoute('productorder_show', array('id' => $productOrder->getId()));
        }

        return $this->render('productorder/new.html.twig', array(
            'productOrder' => $productOrder,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a productOrder entity.
     *
     */
    public function showAction(ProductOrder $productOrder)
    {
        $deleteForm = $this->createDeleteForm($productOrder);

        return $this->render('productorder/show.html.twig', array(
            'productOrder' => $productOrder,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productOrder entity.
     *
     */
    public function editAction(Request $request, ProductOrder $productOrder)
    {
        $deleteForm = $this->createDeleteForm($productOrder);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\ProductOrderType', $productOrder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('productorder_edit', array('id' => $productOrder->getId()));
        }

        return $this->render('productorder/edit.html.twig', array(
            'productOrder' => $productOrder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a productOrder entity.
     *
     */
    public function deleteAction(Request $request, ProductOrder $productOrder)
    {
        $form = $this->createDeleteForm($productOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productOrder);
            $em->flush();
        }

        return $this->redirectToRoute('productorder_index');
    }

    /**
     * Creates a form to delete a productOrder entity.
     *
     * @param ProductOrder $productOrder The productOrder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProductOrder $productOrder)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('productorder_delete', array('id' => $productOrder->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
