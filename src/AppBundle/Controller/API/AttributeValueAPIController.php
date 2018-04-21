<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Services\AttributeService;
use BPashkevich\ProductBundle\Services\AttributeValueService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AttributeValueAPIController extends Controller
{
    /**
     * @var AttributeValueService
     */
    private $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }

    public function getALLAttributeValuesAction()
    {
        return $this->attributeValueService->getAllAttributeValues();
    }

    public function getAttributeValieByIdAction(Request $request)
    {
        $id = $request->get('id');
        return $this->attributeValueService->findAttributeValues(array('id' => $id));
    }

}
