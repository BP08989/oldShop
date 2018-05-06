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
        $attributeValues = $this->attributeValueService->getAllAttributeValues();
        $attributeMainInfo = array();
        foreach ($attributeValues as $attributeValue) {
            $attributeMainInfo[] = $this->attributeValueService->getMainInfo($attributeValue);
        }

        return $attributeMainInfo;
    }

    public function getAttributeValueByIdAction(Request $request)
    {
        $id = $request->get('id');
        $value = $this->attributeValueService->findAttributeValues(array('id' => $id))[0];

        return $this->attributeValueService->getMainInfo($value);
    }

}
