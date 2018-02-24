<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\AttributeValueService;
use BPashkevich\ProductBundle\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Attributevalue controller.
 *
 */
class AttributeValueController extends Controller
{
    private $attributeValueService;

    private $categoryService;

    private $attributeService;

    public function __construct(AttributeValueService $attributeValueService, CategoryService $categoryService,
                                AttributeService $attributeService)
    {
        $this->attributeValueService = $attributeValueService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
    }

    /**
     * Lists all attributeValue entities.
     *
     */
    public function indexAction()
    {
        return $this->render('attributevalue/index.html.twig', array(
            'attributeValues' => $this->attributeValueService->getAllAttributeValues(),
        ));
    }

    /**
     * Creates a new attributeValue entity.
     *
     */
    public function newAction(Request $request)
    {
        $attributeValue = new Attributevalue();
        $form = $this->createForm('BPashkevich\ProductBundle\Form\AttributeValueType', $attributeValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attribute = $this->attributeService->findAttributes(array(
                'id' => $request->get('attribute'),
            ))[0];
            $attributeValue->setAttribute($attribute);
            $this->attributeValueService->createAttributeValue($attributeValue);

            return $this->redirectToRoute('attributevalue_show', array('id' => $attributeValue->getId()));
        }

        return $this->render('attributevalue/new.html.twig', array(
            'attributeValue' => $attributeValue,
            'attributes' => $this->attributeService->findAttributes(array('mandatory' => 0,)),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a attributeValue entity.
     *
     */
    public function showAction(AttributeValue $attributeValue)
    {
        $deleteForm = $this->createDeleteForm($attributeValue);

        return $this->render('attributevalue/show.html.twig', array(
            'attributeValue' => $attributeValue,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing attributeValue entity.
     *
     */
    public function editAction(Request $request, AttributeValue $attributeValue)
    {
        $deleteForm = $this->createDeleteForm($attributeValue);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\AttributeValueType', $attributeValue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('attributevalue_edit', array('id' => $attributeValue->getId()));
        }

        return $this->render('attributevalue/edit.html.twig', array(
            'attributeValue' => $attributeValue,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a attributeValue entity.
     *
     */
    public function deleteAction(Request $request, AttributeValue $attributeValue)
    {
        $form = $this->createDeleteForm($attributeValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attributeValue);
            $em->flush();
        }

        return $this->redirectToRoute('attributevalue_index');
    }

    /**
     * Creates a form to delete a attributeValue entity.
     *
     * @param AttributeValue $attributeValue The attributeValue entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AttributeValue $attributeValue)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attributevalue_delete', array('id' => $attributeValue->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
