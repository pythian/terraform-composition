<?php

namespace Concrete\Core\Attribute\Command;

use Concrete\Core\Attribute\AttributeKeyInterface;
use Concrete\Core\Attribute\ObjectInterface;
use Concrete\Core\Foundation\Command\Command;

/**
 * Saves a group of attribute keys against an object.
 *
 * Class SaveAttributesCommand
 * @package Concrete\Core\Attribute\Command
 */
class SaveAttributesCommand extends Command
{

    /**
     * @var AttributeKeyInterface[]
     */
    protected $attributeKeys;

    /**
     * @var ObjectInterface
     */
    protected $object;

    /**
     * SaveAttributesCommand constructor.
     * @param AttributeKeyInterface[] $attributeKeys
     * @param ObjectInterface $object
     */
    public function __construct(array $attributeKeys, ObjectInterface $object)
    {
        $this->attributeKeys = $attributeKeys;
        $this->object = $object;
    }

    /**
     * @return AttributeKeyInterface[]
     */
    public function getAttributeKeys(): array
    {
        return $this->attributeKeys;
    }

    /**
     * @return ObjectInterface
     */
    public function getObject(): ObjectInterface
    {
        return $this->object;
    }




}
