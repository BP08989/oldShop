<?php

namespace AppBundle\Controller\API;

use BPashkevich\ProductBundle\Services\AttributeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AttributeAPIController extends Controller
{
    /**
     * @var AttributeService
     */
    private $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    public function getALLAttributesAction()
    {
        $attributes = $this->attributeService->getAllAttributes();

        $attributesMainInfo = array();
        foreach ($attributes as $attribute) {
            $attributesMainInfo[] = $this->attributeService->getMainInfo($attribute);
        }

        return $attributesMainInfo;
    }

    public function getAttributeByIdAction(Request $request)
    {
        $id = $request->get('id');
        $attribute = $this->attributeService->findAttributes(array('id' => $id))[0];
        return $this->attributeService->getMainInfo($attribute);
    }

    public function getMandatoryAttributesAction()
    {
        return $this->attributeService->findAttributes(array('mandatory' => 1,));
    }

    public function getNotMandatoryAttributesAction()
    {
        return $this->attributeService->findAttributes(array('mandatory' => 0,));
    }
}
