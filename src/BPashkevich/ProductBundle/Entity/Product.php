<?php

namespace BPashkevich\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="BPashkevich\ProductBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="material", type="string", length=255)
     */
    private $material;

    /**
     * @var int|null
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="shortDescription", type="string", length=50, nullable=true)
     */
    private $shortDescription;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quanity", type="integer", nullable=true)
     */
    private $quanity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=7, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="product")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product")
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity="ProductOrder", inversedBy="products")
     */
    private $productOrders;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productOrders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set brand.
     *
     * @param string $brand
     *
     * @return Product
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set material.
     *
     * @param string $material
     *
     * @return Product
     */
    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material.
     *
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set weight.
     *
     * @param int|null $weight
     *
     * @return Product
     */
    public function setWeight($weight = null)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return int|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set size.
     *
     * @param string $size
     *
     * @return Product
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set shortDescription.
     *
     * @param string|null $shortDescription
     *
     * @return Product
     */
    public function setShortDescription($shortDescription = null)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription.
     *
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set quanity.
     *
     * @param int|null $quanity
     *
     * @return Product
     */
    public function setQuanity($quanity = null)
    {
        $this->quanity = $quanity;

        return $this;
    }

    /**
     * Get quanity.
     *
     * @return int|null
     */
    public function getQuanity()
    {
        return $this->quanity;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category.
     *
     * @param \BPashkevich\ProductBundle\Entity\Category|null $category
     *
     * @return Product
     */
    public function setCategory(\BPashkevich\ProductBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \BPashkevich\ProductBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add image.
     *
     * @param \BPashkevich\ProductBundle\Entity\Image $image
     *
     * @return Product
     */
    public function addImage(\BPashkevich\ProductBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \BPashkevich\ProductBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\BPashkevich\ProductBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add productOrder.
     *
     * @param \BPashkevich\ProductBundle\Entity\ProductOrder $productOrder
     *
     * @return Product
     */
    public function addProductOrder(\BPashkevich\ProductBundle\Entity\ProductOrder $productOrder)
    {
        $productOrder->addProduct($this);
        $this->productOrders[] = $productOrder;

        return $this;
    }

    /**
     * Remove productOrder.
     *
     * @param \BPashkevich\ProductBundle\Entity\ProductOrder $productOrder
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductOrder(\BPashkevich\ProductBundle\Entity\ProductOrder $productOrder)
    {
        return $this->productOrders->removeElement($productOrder);
    }

    /**
     * Get productOrders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductOrders()
    {
        return $this->productOrders;
    }
}
