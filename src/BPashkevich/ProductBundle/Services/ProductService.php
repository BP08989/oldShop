<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductService
{
    private $em;

    private $session;

    public function __construct(\Doctrine\ORM\EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
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

    public function createProduct(Product $product)
    {
//        $product->setSeller($this->session->get('currentUserName'))

        $product->setSeller("Admin");

        $product->setCreatedAt(date('jS \of F Y h:i:s A'));

        $this->em->persist($product);
        $this->em->flush();
        return $product;
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