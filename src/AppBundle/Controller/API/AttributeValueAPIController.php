<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Entity\AttributeValue;
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

    public function saveAttributeValueAction(Request $request)
    {
        $id = $request->get('id');
        $value =  new AttributeValue();
        $value->setValue($request->get('value'));
        $value->setAttribute($request->get('attribute'));
        if ($id) {
            $this->attributeValueService->editAttributeValue($value);
            $value = $this->attributeValueService->findAttributeValues(array('id' => $id))[0];
        } else {
            $value = $this->attributeValueService->createAttributeValue($value);
        }

        return $this->attributeValueService->getMainInfo($value);
    }

    public function deleteAttributeValueAction(Request $request)
    {
        $id = $request->get('id');
        $value = $this->attributeValueService->findAttributeValues(array('id' => $id))[0];
        $this->attributeValueService->deleteAttributeValue($value);

        return true;
    }

    public function deleteALLAttributeValuesAction(Request $request)
    {
        $values = $this->attributeValueService->getAllAttributeValues();
        foreach ($values as $value) {
            $this->attributeValueService->deleteAttributeValue($value);
        }

        return true;
    }
}
