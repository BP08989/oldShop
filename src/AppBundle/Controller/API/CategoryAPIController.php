<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BPashkevich\ProductBundle\Services\CategoryService;
use Symfony\Component\HttpFoundation\Request;

class CategoryAPIController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getALLCategoriesAction()
    {
        return $this->categoryService->getAllCategories();
    }

    public function getCategoryByIdAction(Request $request)
    {
        $id = $request->get('id');
        return $this->categoryService->findCategories(array('id' => $id));
    }

}
