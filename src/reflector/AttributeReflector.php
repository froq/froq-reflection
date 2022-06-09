<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\reflector;

/**
 * Attribute reflector class.
 *
 * @package froq\reflection\reflector
 * @object  froq\reflection\reflector\AttributeReflector
 * @author  Kerem Güneş
 * @since   6.0
 */
class AttributeReflector extends Reflector
{
    /**
     * Set of attributes.
     *
     * @return Set<ReflectionAttribute>
     */
    public function attributes(): \Set
    {
        return new \Set($this->reflector->getAttributes());
    }

    /**
     * Check whether an attribute exists by given name.
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        foreach ($this->reflector->getAttributes() as $attribute) {
            if ($attribute->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get an attribute by given name or return null if absent.
     *
     * @param  string $name
     * @return ReflectionAttribute|null
     */
    public function getAttribute(string $name): \ReflectionAttribute|null
    {
        foreach ($this->reflector->getAttributes() as $attribute) {
            if ($attribute->getName() == $name) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * Get all attribute names.
     *
     * @return array<string>
     */
    public function getAttributeNames(): array
    {
        $names = [];
        foreach ($this->reflector->getAttributes() as $attribute) {
            $names[] = $attribute->getName();
        }
        return $names;
    }
}
