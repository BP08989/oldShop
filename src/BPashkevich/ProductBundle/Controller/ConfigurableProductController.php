<?php

namespace BPashkevich\ProductBundle\Controller;

use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\ConfigurableProduct;
use BPashkevich\ProductBundle\Entity\Image;
use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\AttributeValueService;
use BPashkevich\ProductBundle\Services\CategoryService;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use BPashkevich\ProductBundle\Services\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Configurableproduct controller.
 *
 */
class ConfigurableProductController extends Controller
{
    private $configurableProductService;

    private $categoryService;

    private $attributeService;

    private $attributeValueService;

    private $imageService;

    public function __construct(ConfigurableProductService $configurableProductService, CategoryService $categoryService,
                                ImageService $imageService, AttributeService $attributeService,
                                AttributeValueService $attributeValueService)
    {
        $this->configurableProductService = $configurableProductService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
        $this->attributeValueService = $attributeValueService;
        $this->imageService = $imageService;
    }

    /**
     * Lists all configurableProduct entities.
     *
     */
    public function indexAction()
    {
        return $this->render('product/index.html.twig', array(
            'products' => $this->configurableProductService->getAllProduct(),
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    /**
     * Creates a new configurableProduct entity.
     *
     */
    public function newAction(Request $request)
    {
        return $this->render('configurableproduct/new.html.twig', array(
            'requireAttributes' => $this->attributeService->findAttributes(array('mandatory' => 1,)),
            'notRequireAttributes' => $this->attributeService->findAttributes(array('mandatory' => 0,)),
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    public function saveAction(Request $request)
    {
        $product = new ConfigurableProduct();
        $requestData = $request->request->all();
        $requestDataKeys = array_keys($requestData);
        $attributes = [];
        $attributesValues = [];
        $category = new Category();
        $image = new Image();

        $counter = 0;
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
                    $attributes[$counter] = $this->attributeService->findAttributes(array('id' => $key,))[0];
                    $counter++;
                    break;
            }
        }

        unset($requestData['category']);
        unset($requestData['image']);

        $counter=0;
        foreach ($requestData as $attribute){
            $product->addAttribure($attributes[$counter]);
            if($attributes[$counter]->getMandatory()){
                $attributeValue = new AttributeValue();
                $attributeValue->setAttribute($attributes[$counter]);
                $attributeValue->setValue($attribute);
                $this->attributeValueService->createAttributeValue($attributeValue);
                $attributesValues[$counter] = $attributeValue;
            }
            else{
                unset($attributes[$counter]);
            }
            $counter++;
        }
        sort($attributes);

        $product->setCategory($category);
        $this->configurableProductService->createProduct($product, $attributes, $attributesValues);
        $image->setConfigurableProduct($product);
        $this->imageService->createImage($image);

        var_dump($product);
        die();

        return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    /**
     * Finds and displays a configurableProduct entity.
     *
     */
    public function showAction(ConfigurableProduct $configurableProduct)
    {
        $params = $this->configurableProductService->getSimplesParams($configurableProduct);
//        die(var_dump($params));

        $params = json_encode($params);

//        die(var_dump($params));

//        return $params;

        return $this->render('configurableproduct/show.html.twig', array(
            'product' => $configurableProduct,
            'data' => $this->configurableProductService->getAttributesValues($configurableProduct),
            'categoryName' => $configurableProduct->getCategory(),
            'options' => $params,
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    public function updateAction(Request $request, ConfigurableProduct $configurableProduct)
    {
        var_dump($configurableProduct->getAttribures());
        die();

        return $this->render('configurableproduct/edit.html.twig', array(
            'configurableProduct' => $configurableProduct,
            'requireAttributes' => $this->attributeService->findAttributes(array('mandatory' => 1,)),
            'notRequireAttributes' => $configurableProduct->getAttribures(),
            'categories' => $this->categoryService->getAllCategories(),
        ));
    }

    /**
     * Displays a form to edit an existing configurableProduct entity.
     *
     */
    public function editAction(Request $request, ConfigurableProduct $configurableProduct)
    {
        $deleteForm = $this->createDeleteForm($configurableProduct);
        $editForm = $this->createForm('BPashkevich\ProductBundle\Form\ConfigurableProductType', $configurableProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('configurableproduct_edit', array('id' => $configurableProduct->getId()));
        }

        return $this->render('configurableproduct/edit.html.twig', array(
            'configurableProduct' => $configurableProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a configurableProduct entity.
     *
     */
    public function deleteAction(Request $request, ConfigurableProduct $configurableProduct)
    {
        $form = $this->createDeleteForm($configurableProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($configurableProduct);
            $em->flush();
        }

        return $this->redirectToRoute('configurableproduct_index');
    }

    /**
     * Creates a form to delete a configurableProduct entity.
     *
     * @param ConfigurableProduct $configurableProduct The configurableProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ConfigurableProduct $configurableProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurableproduct_delete', array('id' => $configurableProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
