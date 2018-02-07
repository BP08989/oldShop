<?php

namespace BPashkevich\ProductBundle\DataFixtures\ORM;

use BPashkevich\ProductBundle\Entity\Category;
use BPashkevich\ProductBundle\Entity\Product;
use BPashkevich\ProductBundle\Entity\Image;
use BPashkevich\ProductBundle\Entity\ProductOrder;
use BPashkevich\UserBundle\Entity\User;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommonData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setLogin('firstUser')
            ->setPassword('password')
            ->setEmail('FUser@mail.ru')
            ->setPhoneNumber('375292098505');

        $manager->persist($user);

        $category = new Category();
        $category
            ->setName('Books');

        $manager->persist($category);

        $product = new Product();
        $product
            ->setName('Book')
            ->setBrand('BookStoreBrand')
            ->setMaterial('Paper')
            ->setWeight('300gr')
            ->setSize('14x15cm')
            ->setDescription('A small book about smth.')
            ->setShortDescription('A small book about smth.')
            ->setQuanity(100)
            ->setPrice(4.99)
            ->setCategory($category);

        $manager->persist($product);

        $image = new Image();
        $image
            ->setUrl('http://res.cloudinary.com/sleepingpanda/image/upload/v1517752730/book.png')
            ->setProduct($product);

        $manager->persist($image);

        $productOrder = new ProductOrder();

        $manager->persist($productOrder);

        $category->addProduct($product);

        $product->addImage($image);
        $product->addProductOrder($productOrder);

        $productOrder->addProduct($product);
        $productOrder->addUser($user);

        $user->addProductOrder($productOrder);

        $manager->flush();
    }
}