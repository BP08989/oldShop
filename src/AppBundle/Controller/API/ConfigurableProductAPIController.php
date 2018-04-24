<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Services\ConfigurableProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConfigurableProductAPIController extends Controller
{
    /**
     * @var ConfigurableProductService
     */
    private $configurableProductService;

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
}
