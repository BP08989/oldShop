<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Attribute;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Attribute controller.
 *
 */
class AttributeController extends Controller
{
    /**
     * Lists all attribute entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $attributes = $em->getRepository('BPashkevichProductBundle:Attribute')->findAll();

        return $this->render('attribute/index.html.twig', array(
            'attributes' => $attributes,
        ));
    }

    /**
     * Creates a new attribute entity.
     *
     */
    public function newAction(Request $request)
    {
        $attribute = new Attribute();
        $form = $this->createForm('BPashkevich\ProductBundle\Form\AttributeType', $attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribute);
            $em->flush();

            return $this->redirectToRoute('attribute_show', array('id' => $attribute->getId()));
        }

        return $this->render('attribute/new.html.twig', array(
            'attribute' => $attribute,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a attribute entity.
     *
     */
    public function showAction(Attribute $attribute)
    {
        $deleteForm = $this->createDeleteForm($attribute);

        return $this->render('attribute/show.html.twig', array(
            'attribute' => $attribute,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing attribute entity.
     *
     */
    public function editAction(Request $request, Attribute $attribute)
    {
        $deleteForm = $this->createDeleteForm($attribute);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\AttributeType', $attribute);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('attribute_edit', array('id' => $attribute->getId()));
        }

        return $this->render('attribute/edit.html.twig', array(
            'attribute' => $attribute,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a attribute entity.
     *
     */
    public function deleteAction(Request $request, Attribute $attribute)
    {
        $form = $this->createDeleteForm($attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attribute);
            $em->flush();
        }

        return $this->redirectToRoute('attribute_index');
    }

    /**
     * Creates a form to delete a attribute entity.
     *
     * @param Attribute $attribute The attribute entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Attribute $attribute)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attribute_delete', array('id' => $attribute->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
