<?php

namespace BPashkevich\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttributeValue
 *
 * @ORM\Table(name="attribute_value")
 * @ORM\Entity(repositoryClass="BPashkevich\ProductBundle\Repository\AttributeValueRepository")
 */
class AttributeValue
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Attribute", inversedBy="attributeValue")
     */
    private $attribute;


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
     * Set value.
     *
     * @param string $value
     *
     * @return AttributeValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attribute.
     *
     * @param \BPashkevich\ProductBundle\Entity\Attribute|null $attribute
     *
     * @return AttributeValue
     */
    public function setAttribute(\BPashkevich\ProductBundle\Entity\Attribute $attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return \BPashkevich\ProductBundle\Entity\Attribute|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
}
