<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\Image;
use BPashkevich\ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductAPIController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;
    private $categoryService;
    private $attributeService;
    private $attributeValueService;
    private $imageService;
    private $configurableProductService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getALLProductsAction()
    {
        $products = $this->productService->getAllProduct();
        $currentProducts = array();
        foreach ($products as $produt) {
            $currentProducts[] = $this->productService->getMainInfo($produt);
        }

        return $currentProducts;
    }

    public function getProductByIdAction(Request $request)
    {
        $product = $this->productService->findProducts(array('id' => $request->get('id')))[0];
        $currentProduct = $this->productService->getMainInfo($product);

        return $currentProduct;
    }

    public function getProductsByParamAction(Request $request)
    {
        $currentProducts = array();
        $products = $this->productService->getAllProduct();
        foreach ($products as $product) {
            if($this->productService->getShortInfo($product, 'Name') == $request->get('value')) {
                $currentProducts[] = $this->productService->getMainInfo($product);
            }
        }

        return $currentProducts;
    }

    public function getProductCategoryAction(Request $request)
    {
        $product = $this->productService->findProducts(array('id' => $request->get('id')))[0];

        return array(
            'id' => $product->getCategory()->getId(),
            'name' => $product->getCategory()->getName()
        );
    }

    public function getProductDataAction(Request $request) {
        $product = $this->productService->findProducts(array('id' => $request->get('id')))[0];

        return $this->productService->getAttributesValues($product);
    }

    public function getSingleProductMainInfoAction(Request $request)
    {
        return $this->productService->getSingleProductMainInfo($request->get('id'));
    }

    public function getAttributesAction()
    {
        $products = $this->productService->getAllProduct();
        $attributes = [];
        foreach ($products as $product){
            $attributes[$product->getId()] = $product->getAttributes();
        }
        return $attributes;
    }


    public function saveProductAction(Request $request)
    {
        $product = $this->productService->findProducts(array('id' => $request->get('id')))[0];
        $configurableProduct = $product->getConfigurableProduct();

        $product = new Product();
        $requestData = $request->request->all();

        if (configurableProduct()){
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

        return $this->productService->getMainInfo($product);
    }

    public function deleteProductAction(Request $request)
    {
        $id = $request->get('id');
        $product = $this->productService->findProducts(array('id' => $id))[0];
        $this->productService->deleteProduct($product);

        return true;
    }

    public function deleteALLProductsAction(Request $request)
    {
        $products = $this->productService->getAllProduct();
        foreach ($products as $product) {
            $this->productService->deleteProduct($product);
        }

        return true;
    }
}
