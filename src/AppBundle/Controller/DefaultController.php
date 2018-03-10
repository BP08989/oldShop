<?php

namespace AppBundle\Controller;

use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
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

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $products = $this->productService->findProducts(array('configurableProduct' => null));
        $cProducts = $this->configurableProductService->getAllProduct();
        $productsAttr = array();
        $cProductsAttr = array();

        foreach ($products as $product){
            $productsAttr[$product->getId()]['Name'] = $this->productService->getShortInfo($product, 'Name');
            $productsAttr[$product->getId()]['ShortDescription'] = $this->productService->getShortInfo($product, 'Short description');
            $productsAttr[$product->getId()]['Price'] = $this->productService->getShortInfo($product, 'Price');
        }

        foreach ($cProducts as $cProduct){
            $cProductsAttr[$cProduct->getId()]['Name'] = $this->configurableProductService
                ->getShortInfo($cProduct, 'Name');
            $cProductsAttr[$cProduct->getId()]['ShortDescription'] = $this->configurableProductService
                ->getShortInfo($cProduct, 'Short description');
            $cProductsAttr[$cProduct->getId()]['Price'] = $this->configurableProductService
                ->getShortInfo($cProduct, 'Price');
        }

//        die(var_dump($cProductsAttr));

        return $this->render('default/home.html.twig', [
            'categories' => $this->categoryService->getAllCategories(),
            'products' => $products,
            'productsAttr' => $productsAttr,
            'configurableProducts' => $cProducts,
            'configurableProductsAttr' => $cProductsAttr,
        ]);
    }
}
