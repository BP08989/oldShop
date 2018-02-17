<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Services\ProductService;
use BPashkevich\ProductBundle\Services\CategoryService;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     */

    public function indexAction()
    {
//        $images = $this->getDoctrine()
//            ->getRepository("BPashkevichProductBundle:Product")
//            ->find(1)->getImages()->get(0)->getUrl();

//        $urls = [];
//        foreach($products as $product) {
//            $urls[] = $product->getImages()->get(0)->getUrl();
//        }

        return $this->render('product/index.html.twig', array(
            'products' => $this->get('b_pashkevich_product.product_srvice')->getAllProduct(),
            'categories' => $this->get('b_pashkevich_product.category_srvice')->getAllCategories(),
        ));
    }

    /**
     * Creates a new product entity.
     *
     */

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requireAttributes = $em->getRepository('BPashkevichProductBundle:Attribute')->findBy(array(
            'require' => 1,
        ));

        return $this->render('product/new.html.twig', array(
            'requireAttributes' => $requireAttributes,
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
                    $category = $em->getRepository('BPashkevichProductBundle:Category')->find($requestData[$requestDataKeys[$counter]]);
                    break;
                case "image":
                    $image->setUrl($requestData[$requestDataKeys[$counter]]);
                    break;
                default:
                    $attributes[$counter] = $em->getRepository('BPashkevichProductBundle:Attribute')->find($key);

                    break;
            }
            $counter++;
        }

        $counter=0;
        foreach ($requestData as $attribute){
            switch ($requestDataKeys[$counter]){
                case "category":
                    break;
                case "image":
                    break;
                default:
                    $attributeValue = new AttributeValue();
                    $attributeValue->setAttribute($attributes[$counter]);
                    $attributeValue->setValue($attribute);
                    $em->persist($attributeValue);
                    $em->flush();
                    $attributesValues[$counter] = $attributeValue;
                    $counter++;
                    break;
            }
        }

        $product = new Product();
        $product->setCategory($category);
        $em->persist($product);
        $em->flush();

        $image->setProduct($product);

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
            $queryBuilder->insert('product_attribute_value')
                ->setValue('product_id', $product->getId())
                ->setValue('attribute_id', $attributes[$i]->getId())
                ->setValue('value_id', $attributesValues[$i]->getId());
            $queryBuilder->execute();
        }

        var_dump($category);
        die();

        return $this->redirectToRoute('product_show', array('id' => $product->getId()));

    }

    /**
     * Finds and displays a product entity.
     *
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'categoryName' => $this->get('b_pashkevich_product.category_srvice')->getCategoryById($product->getCategory()),
            'delete_form' => $deleteForm->createView(),
            'categories' => $this->get('b_pashkevich_product.category_srvice')->getAllCategories(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
