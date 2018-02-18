<?php

namespace BPashkevich\ProductBundle\Services;

use BPashkevich\ProductBundle\Entity\Image;

class ImageService
{
    private $em;

    private  $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('BPashkevichProductBundle:Image');
    }

    public function getAllImages()
    {
        return $this->repository->findAll();
    }

    public function findImages(array $params)
    {
        return $this->repository->findBy($params);
    }

    public function createImage(Image $image)
    {
        $this->em->persist($image);
        $this->em->flush();
        return $image;
    }

    public function editImage(Image $image)
    {
        $this->em->flush();
        return $image;
    }

    public function deleteImage(Image $image)
    {
        $this->em->remove($image);
        $this->em->flush();
    }
}