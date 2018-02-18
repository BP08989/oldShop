<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\ConfigurableProduct;
use BPashkevich\ProductBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Configurableproduct controller.
 *
 */
class ConfigurableProductController extends Controller
{
    /**
     * Lists all configurableProduct entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $configurableProducts = $em->getRepository('BPashkevichProductBundle:ConfigurableProduct')->findAll();

        return $this->render('configurableproduct/index.html.twig', array(
            'configurableProducts' => $configurableProducts,
        ));
    }

    /**
     * Creates a new configurableProduct entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requireAttributes = $em->getRepository('BPashkevichProductBundle:Attribute')->findBy(array(
            'require' => 1,
        ));

        $notRequireAttributes = $em->getRepository('BPashkevichProductBundle:Attribute')->findBy(array(
            'require' => 0,
        ));

        return $this->render('configurableproduct/new.html.twig', array(
            'requireAttributes' => $requireAttributes,
            'notRequireAttributes' => $notRequireAttributes,
            'categories' => $em->getRepository('BPashkevichProductBundle:Category')->findAll(),
        ));
    }

    public function saveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestData = $request->request->all();
        $requestDataKeys = array_keys($requestData);
        $attributes = [];
        $attributesValues = [];
        $category = new Category();
        $image = new Image();

        $counter = 0;
        foreach ($requestDataKeys as $key){
            switch ($key){
                case "category":
                    $category = $em->getRepository('BPashkevichProductBundle:Category')->find($requestData[$key]);
                    unset($requestDataKeys[$counter]);
                    sort($requestDataKeys);
                    break;
                case "image":
                    $image->setUrl($requestData[$key]);
                    unset($requestDataKeys[$counter]);
                    sort($requestDataKeys);
                    break;
                default:
                    $attributes[$counter] = $em->getRepository('BPashkevichProductBundle:Attribute')->find($key);
                    $counter++;
                    break;
            }
        }

        $counter=0;
        foreach ($requestData as $value){
                    if($attributes[$counter]->getRequire()){
                        $attributeValue = new AttributeValue();
                        $attributeValue->setAttribute($attributes[$counter]);
                        $attributeValue->setValue($value);
                        $em->persist($attributeValue);
                        $em->flush();
                        $attributesValues[$counter] = $attributeValue;
                    }
                    else{
                        var_dump($attributes[$counter]);
                        die();
                    }
                    $counter++;
        }


        $product = new ConfigurableProduct();
        $product->setCategory($category);
        $em->persist($product);
        $em->flush();

        $image->setConfigurableProduct($product);

        $em->persist($image);
        $em->flush();

        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => 'oldShop',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $queryBuilder = $conn->createQueryBuilder();

        for ($i=0; $i<$counter; $i++) {
            $queryBuilder->insert('configurable_product_attribute_value')
                ->setValue('product_id', $product->getId())
                ->setValue('attribute_id', $attributes[$i]->getId())
                ->setValue('value_id', $attributesValues[$i]->getId());
            $queryBuilder->execute();
        }

        var_dump($attributes);
        die();
        return $this->redirectToRoute('product_show', array('id' => $product->getId()));

    }

    /**
     * Finds and displays a configurableProduct entity.
     *
     */
    public function showAction(ConfigurableProduct $configurableProduct)
    {
        $deleteForm = $this->createDeleteForm($configurableProduct);

        return $this->render('configurableproduct/show.html.twig', array(
            'configurableProduct' => $configurableProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing configurableProduct entity.
     *
     */
    public function editAction(Request $request, ConfigurableProduct $configurableProduct)
    {
        $deleteForm = $this->createDeleteForm($configurableProduct);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\ConfigurableProductType', $configurableProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('configurableproduct_edit', array('id' => $configurableProduct->getId()));
        }

        return $this->render('configurableproduct/edit.html.twig', array(
            'configurableProduct' => $configurableProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a configurableProduct entity.
     *
     */
    public function deleteAction(Request $request, ConfigurableProduct $configurableProduct)
    {
        $form = $this->createDeleteForm($configurableProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($configurableProduct);
            $em->flush();
        }

        return $this->redirectToRoute('configurableproduct_index');
    }

    /**
     * Creates a form to delete a configurableProduct entity.
     *
     * @param ConfigurableProduct $configurableProduct The configurableProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ConfigurableProduct $configurableProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurableproduct_delete', array('id' => $configurableProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
