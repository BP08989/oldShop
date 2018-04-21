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
        $id = $request->get('id');
        return $this->productService->findProducts(array('id' => $id))[0];
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
