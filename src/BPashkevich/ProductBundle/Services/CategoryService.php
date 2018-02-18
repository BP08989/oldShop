<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Category;

class CategoryService
{
    private $em;

    private $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:Category');
    }

    public function getAllCategories()
    {
        return $this->repository->findAll();
    }

    public function findCategories(array $params)
    {
        return $this->repository->findBy($params);
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