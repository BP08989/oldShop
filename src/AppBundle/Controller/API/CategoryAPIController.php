<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\ProductBundle\Services\CategoryService;
use Symfony\Component\HttpFoundation\Request;

class CategoryAPIController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var ConfigurableProductService
     */
    private $configurableProductService;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
        ConfigurableProductService $configurableProductService
    ) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->configurableProductService = $configurableProductService;
    }

    public function getALLCategoriesAction()
    {
        return $this->categoryService->getAllCategories();
    }

    public function getCategoryShortInfoAction(Request $request)
    {
        return $this->categoryService->getShortInfo($request->get('id'));
    }

    public function getCategoryByIdAction(Request $request)
    {
        $id = $request->get('id');

        return $this->categoryService->findCategories(array('id' => $id));
    }

    public function getSimpleProductsFromCategoryMainInfoAction(Request $request)
    {
        $id = $request->get('id');
        $category = $this->categoryService->findCategories(array('id' => $id))[0];
        $products = $this->productService->selectSingleProducts($category->getProducts());
        $result = array();
        foreach ($products as $product) {
            $result[] = $this->productService->getMainInfo($product);
        }

        return $result;
    }

    public function getProductsFromCategoryMainInfoAction(Request $request)
    {
        $id = $request->get('id');
        $category = $this->categoryService->findCategories(array('id' => $id))[0];
        $products = $category->getProducts();
        $result = array();
        foreach ($products as $product) {
            $result[] = $this->productService->getMainInfo($product);
        }

        return $result;
    }

    public function getConfigProductFromCategoryMainInfoAction(Request $request)
    {
        $id = $request->get('id');
        $category = $this->categoryService->findCategories(array('id' => $id))[0];
        $products = $category->getConfigurableProducts();
        $result = array();
        foreach ($products as $product) {
            $result[] = $this->configurableProductService->getMainInfo($product);
        }

        return $result;
    }

    public function saveCategoryAction(Request $request)
    {
        $id = $request->get('id');
        $category =  new Category();
        $category->setName($request->get('name'));
        if ($id) {
            $this->categoryService->editCategory($category);
            $category = $this->categoryService->findCategories(array('id' => $id))[0];
        } else {
            $category = $this->categoryService->createCategory($category);
        }

        return $this->categoryService->getShortInfo($category->getId());
    }

    public function deleteCategoryAction(Request $request)
    {
        $id = $request->get('id');
        $category = $this->categoryService->findCategories(array('id' => $id))[0];
        $this->categoryService->findCategories($category);

        return true;
    }

    public function deleteALLCategoriesAction(Request $request)
    {
        $categories = $this->categoryService->getAllCategories();
        foreach ($categories as $category) {
            $this->categoryService->deleteCategory($category);
        }

        return true;
    }
}
