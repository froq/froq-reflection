<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

use froq\reflection\{ReflectionAttribute, ReflectionCallable};
use Set;

/**
 * Attribute reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @class   froq\reflection\internal\reflector\AttributeReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class AttributeReflector extends Reflector
{
    /**
     * Set of attributes.
     *
     * @return Set<froq\reflection\ReflectionAttribute>
     */
    public function attributes(): Set
    {
        return new Set($this->getAttributes());
    }

    /**
     * Check attribute existence.
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        if ($this->collect($name)) {
            return true;
        }
        return false;
    }

    /**
     * Get attribute.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionAttribute|null
     */
    public function getAttribute(string $name): ReflectionAttribute|null
    {
        if ($ret = $this->collect($name)) {
            return $this->convert($ret[0]);
        }
        return null;
    }

    /**
     * Get attributes.
     *
     * @param  string|array|null $name
     * @param  int|null          $flags
     * @return array<froq\reflection\ReflectionAttribute>
     */
    public function getAttributes(string|array $name = null, int $flags = null): array
    {
        return array_apply(
            $this->collect($name),
            fn(\ReflectionAttribute $ref): ReflectionAttribute => $this->convert($ref)
        );
    }

    /**
     * Get attribute names.
     *
     * @return array<string>
     */
    public function getAttributeNames(): array
    {
        return array_apply(
            $this->collect(),
            fn(\ReflectionAttribute $ref): string => $ref->getName()
        );
    }

    /**
     * Collect attributes.
     */
    private function collect(string|array $name = null, int $flags = null): array
    {
        $ref = match (true) {
            default => null,
            $this->reflector instanceof \ReflectionClass
                => new \ReflectionClass($this->reflector->name),
            $this->reflector instanceof \ReflectionClassConstant
                => new \ReflectionClassConstant($this->reflector->class, $this->reflector->name),
            $this->reflector instanceof \ReflectionProperty
                => new \ReflectionProperty($this->reflector->class, $this->reflector->name),
            $this->reflector instanceof \ReflectionMethod,
            $this->reflector instanceof \ReflectionFunction,
            $this->reflector instanceof ReflectionCallable
                => $this->reflector->reference->reflection,
        };

        $ret = [];

        if ($ref) {
            if (is_string($name)) {
                $ret = $ref->getAttributes($name, (int) $flags);
            } else {
                $ret = $ref->getAttributes(null, (int) $flags);

                if ($ret && $name) {
                    $ret = array_filter_list($ret, fn(\ReflectionAttribute $attribute): bool => (
                        in_array($attribute->getName(), $name, true)
                    ));
                }
            }
        }

        return $ret;
    }

    /**
     * Convert attributes to instances.
     */
    private function convert(\ReflectionAttribute $attribute): ReflectionAttribute
    {
        return new ReflectionAttribute($attribute);
    }
}
