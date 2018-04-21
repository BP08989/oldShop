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
}
