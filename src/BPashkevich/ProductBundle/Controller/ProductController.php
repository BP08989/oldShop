<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Attribute;
use BPashkevich\ProductBundle\Entity\ConfigurableProduct;
use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\AttributeValueService;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ImageService;
use BPashkevich\ProductBundle\Services\ProductService;
use BPashkevich\ProductBundle\Services\CategoryService;
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

    private $configurableProductService;

    public function __construct(ProductService $productService, CategoryService $categoryService,
                                ImageService $imageService, AttributeService $attributeService,
                                AttributeValueService $attributeValueService,
                                ConfigurableProductService $configurableProductService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
        $this->attributeValueService = $attributeValueService;
        $this->configurableProductService = $configurableProductService;
        $this->imageService = $imageService;
    }

    /**
     * Lists all product entities.
     *
     */

    public function indexAction()
    {
        $products = $this->productService->getAllProduct();
        $attributes = [];
        foreach ($products as $product){
            $attributes[$product->getId()] = $product->getAttributes();
        }
        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'attributes' => $attributes,
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    /**
     * Creates a new product entity.
     *
     */

    public function newAction(Request $request, ConfigurableProduct $configurableProduct = null)
    {
        $result['attributes'] = $this->attributeService->findAttributes(array('mandatory' => 1,));

        if ($configurableProduct){
            $result['id'] = $configurableProduct->getId();
            $result['configAttr'] = $this->configurableProductService->getNotMandatoryAttributes($configurableProduct);
        }
        else{
            $result['categories'] = $this->categoryService->getAllCategories();
        }

        return $this->render('product/new.html.twig', $result);
    }

    public function saveAction(Request $request, ConfigurableProduct $configurableProduct = null)
    {
        $product = new Product();
        $requestData = $request->request->all();

        if ($configurableProduct){
            $requestData['category'] = $configurableProduct->getCategory()->getId();
            $product->setConfigurableProduct($configurableProduct);
        }

        $requestDataKeys = array_keys($requestData);
        $attributes = [];
        $attributesValues = [];
        $category = new Category();
        $image = new Image();

        foreach ($requestDataKeys as $key){
            switch ($key){
                case "category":
                    $category = $this->categoryService->findCategories(array(
                        'id' => $requestData[$key],
                        ))[0];
                    break;
                case "image":
                    $image->setUrl($requestData[$key]);
                    break;
                default:
                    $attributes[] = $this->attributeService->findAttributes(array('id' => $key,))[0];
                    break;
            }
        }

        unset($requestData['category']);
        unset($requestData['image']);

        $counter = 0;
        foreach ($requestData as $attribute){
            $val = $this->attributeValueService->findAttributeValues(array(
                'attribute' => $attributes[$counter],
                'value' => $attribute,
            ));
            if(!$val){
                $attributeValue = new AttributeValue();
                $attributeValue->setAttribute($attributes[$counter]);
                $attributeValue->setValue($attribute);
                $this->attributeValueService->createAttributeValue($attributeValue);
                $attributesValues[] = $attributeValue;
            }
            else{
                $attributesValues[] = $val[0];
            }
            $counter++;
        }

        $product->setCategory($category);
        $this->productService->createProduct($product, $attributes, $attributesValues);
        $image->setProduct($product);
        $this->imageService->createImage($image);

        return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    public function newProductFromConfigurableAction(){

    }

    /**
     * Finds and displays a product entity.
     *
     */
    public function showAction(Product $product)
    {
//        die(var_dump($this->productService->getAttributesValues($product)));

        return $this->render('product/show.html.twig', array(
            'data' => $this->productService->getAttributesValues($product),
            'product' => $product,
            'categoryName' => $product->getCategory(),
            'categories' => $this->categoryService->getAllCategories(),
            'isConsist' => in_array($product->getId(), $_SESSION['cart']),
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
