<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cart controller.
 *
 */
class CartController extends Controller
{

    private $categoryService;

    private $productService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function indexAction()
    {
        $products = array();
        $productsAttr = array();
        $totalCost = 0;
        
        if(empty($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }

        $_SESSION['cart'] = array_unique($_SESSION['cart']);

        foreach ($_SESSION['cart'] as $id){
            $products[] = $this->productService->findProducts(array(
                'id' =>  $id,
            ))[0];
        }

        foreach ($products as $product){
            $productsAttr[$product->getId()]['Name'] = $this->productService->getShortInfo($product, 'Name');
            $price = $this->productService->getShortInfo($product, 'Price');
            $productsAttr[$product->getId()]['Price'] = $price;
            $totalCost += $price;
        }

//        die(var_dump($_SESSION['cart']));

        return $this->render('cart/index.html.twig', array(
            'products' => $products,
            'productsAttr' => $productsAttr,
            'categories' => $this->categoryService->getAllCategories(),
            'totalCost' => $totalCost,
        ));
    }

    public function addAction(Product $product)
    {
        if(empty($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }

        array_push($_SESSION['cart'], $product->getId());

        return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    public function removeAction(Product $product)
    {
        if (($key = array_search($product->getId(), $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
        }

        return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    public function clearAction()
    {
        $_SESSION['cart'] = array();

        return $this->redirectToRoute('cart_index');
    }

}
