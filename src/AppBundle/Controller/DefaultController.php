<?php

namespace AppBundle\Controller;

use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class DefaultController extends FOSRestController
{
    private $configurableProductService;

    private $productService;

    private $categoryService;

    public function __construct(CategoryService $categoryService, ProductService $productService,
                                ConfigurableProductService $configurableProductService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->configurableProductService = $configurableProductService;
    }

    public function indexAction(Request $request)
    {
        $products = $this->productService->findProducts(array('configurableProduct' => null));
        $cProducts = $this->configurableProductService->getAllProduct();
        $productsAttr = array();
        $cProductsAttr = array();
        $isProductInCart = array();

        if(empty($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }

        foreach ($products as $product){
            $productsAttr[$product->getId()]['Name'] = $this->productService->getShortInfo($product, 'Name');
            $productsAttr[$product->getId()]['ShortDescription'] = $this->productService->getShortInfo($product, 'Short description');
            $productsAttr[$product->getId()]['Price'] = $this->productService->getShortInfo($product, 'Price');

            $isProductInCart[$product->getId()] = array_search($product->getId(), $_SESSION['cart']);
        }

        foreach ($cProducts as $cProduct){
            $cProductsAttr[$cProduct->getId()]['Name'] = $this->configurableProductService->getShortInfo($cProduct, 'Name');
            $cProductsAttr[$cProduct->getId()]['ShortDescription'] = $this->configurableProductService
                ->getShortInfo($cProduct, 'Short description');
            $cProductsAttr[$cProduct->getId()]['Price'] = $this->configurableProductService->getShortInfo($cProduct, 'Price');
        }

//        die(var_dump($isProductInCart));

        return $this->render('default/home.html.twig', array(
            'categories' => $this->categoryService->getAllCategories(),
            'products' => $products,
            'productsAttr' => $productsAttr,
            'configurableProducts' => $cProducts,
            'configurableProductsAttr' => $cProductsAttr,
            'isProductInCart' => $isProductInCart,
        ));
    }
}
