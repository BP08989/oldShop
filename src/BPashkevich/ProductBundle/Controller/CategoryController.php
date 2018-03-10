<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
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
     * Lists all category entities.
     *
     */
    public function indexAction()
    {
        return $this->render('category/index.html.twig', array(
            'categories' => $this->get('b_pashkevich_product.category_srvice')->getAllCategories(),
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('BPashkevich\ProductBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     */
    public function showAction(Category $category)
    {
        $products = $category->getProducts();
        $products = $this->productService->selectSingleProducts($products);
        $cProducts = $category->getConfigurableProducts();
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
        return $this->render('category/show.html.twig', array(
            'products' => $products,
            'productsAttr' => $productsAttr,
            'configurableProducts' => $cProducts,
            'configurableProductsAttr' => $cProductsAttr,
            'categories' => $this->categoryService->getAllCategories(),
            'category' => $category,
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
