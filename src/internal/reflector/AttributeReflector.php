<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\reflector;

use ReflectionAttribute;
use Set;

/**
 * Attribute reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\AttributeReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class AttributeReflector extends Reflector
{
    /**
     * Set of attributes.
     *
     * @return Set<ReflectionAttribute>
     */
    public function attributes(): Set
    {
        return new Set($this->collect());
    }

    /**
     * Check attribute existence.
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        foreach ($this->collect() as $attribute) {
            if ($attribute->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get attribute.
     *
     * @param  string $name
     * @return ReflectionAttribute|null
     */
    public function getAttribute(string $name): ReflectionAttribute|null
    {
        foreach ($this->collect() as $attribute) {
            if ($attribute->getName() == $name) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * Get attribute names.
     *
     * @return array<string>
     */
    public function getAttributeNames(): array
    {
        return array_map(fn($ref) => $ref->getName(), $this->collect());
    }

    /**
     * Collect attributes.
     */
    private function collect(): array
    {
        return $this->reflector->getAttributes();
    }
}
