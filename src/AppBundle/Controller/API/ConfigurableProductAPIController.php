<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Entity\AttributeValue;
use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\ConfigurableProduct;
use BPashkevich\ProductBundle\Entity\Image;
use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConfigurableProductAPIController extends Controller
{
    /**
     * @var ConfigurableProductService
     */
    private $configurableProductService;

    private $imageService;
    private $categoryService;
    private $attributeService;
    private $attributeValueService;

    public function __construct(ConfigurableProductService $configurableProductService)
    {
        $this->configurableProductService = $configurableProductService;
    }

    public function getALLConfigurableProductsAction()
    {
        return $this->configurableProductService->getAllProduct();
    }

    public function getConfigProductMainInfoAction(Request $request)
    {
        return $this->configurableProductService->getConfigProductMainInfo($request->get('id'));
    }

    public function getConfigurableProductForShowAction(Request $request)
    {
        $configurableProduct = $this->configurableProductService->findProducts(array('id' => $request->get('id')))[0];
        $params = $this->configurableProductService->getSimplesParams($configurableProduct);
        $params = json_encode($params);
        $currentCategory = array(
            'id' => $configurableProduct->getCategory()->getId(),
            'name' => $configurableProduct->getCategory()->getName()
        );
        $data = $this->configurableProductService->getAttributesValues($configurableProduct);
        $configurableProduct = $this->configurableProductService->getMainInfo($configurableProduct);

        return array(
            'product' => $configurableProduct,
            'info' => $data,
            'categoryName' => $currentCategory,
            'options' => $params,
        );
    }

    public function saveConfigurableProductAction(Request $request)
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

        return $this->configurableProductService->getMainInfo($product);
    }

    public function deleteConfigurableProductAction(Request $request)
    {
        $id = $request->get('id');
        $product = $this->configurableProductService->findProducts(array('id' => $id))[0];
        $this->configurableProductService->deleteConfigurableProduct($product);

        return true;
    }

    public function deleteALLConfigurableProductsAction(Request $request)
    {
        $products = $this->configurableProductService->getAllConfigurableProducts();
        foreach ($products as $product) {
            $this->configurableProductService->deleteConfigurableProduct($product);
        }

        return true;
    }
}
