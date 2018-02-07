<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Category;

class CategoryService
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAllCategories()
    {
        return $this->em->getRepository('BPashkevichProductBundle:Category')->findAll();
    }

    public function getCategoryById($id){
        return $this->em->getRepository('BPashkevichProductBundle:Category')->find($id);
    }

    public function createCategory(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    public function editCategory(Category $category)
    {
        $this->em->flush();
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}