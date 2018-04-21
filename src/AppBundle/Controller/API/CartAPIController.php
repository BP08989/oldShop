<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartAPIController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function getALLProductsFromCartAction()
    {
        $products = [];
        if(empty($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }

        $_SESSION['cart'] = array_unique($_SESSION['cart']);

        foreach ($_SESSION['cart'] as $id){
            $products[] = $this->productService->findProducts(array(
                'id' =>  $id,
            ))[0];
        }

        return $products;
    }

}
