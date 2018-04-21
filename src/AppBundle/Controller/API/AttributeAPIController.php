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
        return $this->attributeService->getAllAttributes();
    }

    public function getAttributeByIdAction(Request $request)
    {
        $id = $request->get('id');
        return $this->attributeService->findAttributes(array('id' => $id));
    }

}
