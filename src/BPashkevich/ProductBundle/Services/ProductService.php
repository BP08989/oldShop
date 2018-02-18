<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductService
{
    private $em;

    private $session;

    private $dbService;

    public function __construct(\Doctrine\ORM\EntityManager $em, Session $session, DBService $dbService)
    {
        $this->em = $em;
        $this->session = $session;
        $this->dbService = $dbService;
    }

    public function getAllProduct()
    {
        return $this->em->getRepository('BPashkevichProductBundle:Product')->findAll();
    }

    public function getProductFromCategory($category)
    {
        return  $this->em->getRepository('BPashkevichProductBundle:Product')
            ->findBy( array('category' => $category));
    }

    public function createProduct(Product $product, array $attributes, array $attributeValues)
    {
        if(count($attributes) == count($attributeValues)){
            $queryBuilder = $this->dbService->getQueryBuilder();
//        $product->setSeller($this->session->get('currentUserName'))
            $this->em->persist($product);
            $this->em->flush();
            for ($i=0; $i<count($attributes); $i++) {
                $queryBuilder->insert('product_attribute_value')
                    ->setValue('product_id', $product->getId())
                    ->setValue('attribute_id', $attributes[$i]->getId())
                    ->setValue('value_id', $attributeValues[$i]->getId());
                $queryBuilder->execute();
            }
            return $product;
        }
        return null;
    }

    public function editProduct(Product $product)
    {
        $this->em->flush();
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}