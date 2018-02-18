<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\AttributeValueService;
use BPashkevich\ProductBundle\Services\ImageService;
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
    private $productService;

    private $categoryService;

    private $attributeService;

    private $attributeValueService;

    private $imageService;

    public function __construct(ProductService $productService, CategoryService $categoryService,
                                ImageService $imageService, AttributeService $attributeService,
                                AttributeValueService $attributeValueService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
        $this->attributeValueService = $attributeValueService;
        $this->imageService = $imageService;
    }

    /**
     * Lists all product entities.
     *
     */

    public function indexAction()
    {
        return $this->render('product/index.html.twig', array(
            'products' => $this->productService->getAllProduct(),
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    /**
     * Creates a new product entity.
     *
     */

    public function newAction(Request $request)
    {
        return $this->render('product/new.html.twig', array(
            'requireAttributes' => $this->attributeService->findAttributes(array('require' => 1,)),
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    public function saveAction(Request $request)
    {
        $product = new Product();
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
                    $category = $this->categoryService->findCategories(array(
                        'id' => $requestData[$requestDataKeys[$counter]],
                        ))[0];
                    break;
                case "image":
                    $image->setUrl($requestData[$requestDataKeys[$counter]]);
                    break;
                default:
                    $attributes[$counter] = $this->attributeService->findAttributes(array('id' => $key,))[0];
                    break;
            }
            $counter++;
        }

        unset($requestData['category']);
        unset($requestData['image']);

        $counter=0;
        foreach ($requestData as $attribute){
            $attributeValue = new AttributeValue();
            $attributeValue->setAttribute($attributes[$counter]);
            $attributeValue->setValue($attribute);
            $this->attributeValueService->createAttributeValue($attributeValue);
            $attributesValues[$counter] = $attributeValue;
            $counter++;
        }

        $product->setCategory($category);
        $this->productService->createProduct($product, $attributes, $attributesValues);
        $image->setProduct($product);
        $this->imageService->createImage($image);
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
