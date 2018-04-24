<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Component\HttpFoundation\Request;

class ProductAPIController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getALLProductsAction()
    {
        return $this->productService->getAllProduct();
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

}
