<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionProperty;
use Set;

/**
 * Property reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\PropertyReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class PropertyReflector extends Reflector
{
    /**
     * Set of properties.
     *
     * @return Set<froq\reflection\ReflectionProperty>
     */
    public function properties(): Set
    {
        return (new Set($this->collect()))
            ->map(fn($name) => $this->convert($name));
    }

    /**
     * Check property existence.
     *
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        try {
            // Dynamics allowed by ReflectionProperty if reference is object,
            // but ReflectionClass.hasProperty() return false for them.
            $this->convert($name);
            return true;
        } catch (\Throwable) {
            return false;
        };
    }

    /**
     * Check property existence (but not inherit).
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnProperty(string $name): bool
    {
        // @keep: Why? Cos' return type can change in ClassTrait.getProperty().
        // if (!$this->hasProperty($name)) {
        //     return false;
        // }

        // Can be declared in a super class.
        if ($this->reflector->name !== $this->getProperty($name)?->getDeclaringClass()->name) {
            return false;
        }

        return true;
    }

    /**
     * Get property.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionProperty|null
     */
    public function getProperty(string $name): ReflectionProperty|null
    {
        try {
            // Dynamics allowed by ReflectionProperty if reference is object,
            // but ReflectionClass.getProperty() throws exception for them.
            return $this->convert($name);
        } catch (\Throwable $e) {
            return null;
        };
    }

    /**
     * Get properties.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionProperty>
     */
    public function getProperties(int $filter = null): array
    {
        return array_map([$this, 'convert'], $this->collect($filter));
    }

    /**
     * Get property names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getPropertyNames(int $filter = null): array
    {
        return $this->collect($filter);
    }

    /**
     * Get property values.
     *
     * @param  int|null $filter
     * @param  bool     $assoc
     * @return array<mixed>
     */
    public function getPropertyValues(int $filter = null, bool $assoc = false): array
    {
        // Prevent "non-instantiated class" error.
        $object = is_object($this->reflector->getReference());

        $values = array_map(
            fn($name) => (
                $object ? $this->convert($name)->getValue()
                        : $this->convert($name)->getDefaultValue()
            ),
            $names = $this->collect($filter)
        );

        return $assoc ? array_combine($names, $values) : $values;
    }

    /**
     * Collect properties.
     */
    private function collect(int $filter = null): array
    {
        $reference = $this->reflector->getReference();

        $ret = [];
        $ref = new \ReflectionClass($reference->target);

        foreach ($ref->getProperties($filter) as $property) {
            $ret[$property->name] = $property->name;
        }

        // If no public vanted, skip dynamics.
        if ($filter && !($filter & ReflectionProperty::IS_PUBLIC)) {
            return array_values($ret);
        }

        // Dynamic properties.
        if (is_object($reference->target)) {
            foreach (array_keys(get_object_vars($reference->target)) as $var) {
                array_key_exists($var, $ret) || $ret[$var] = $var;
            }
        }

        return array_values($ret);
    }

    /**
     * Convert properties to instances.
     */
    private function convert(string $name): ReflectionProperty
    {
        return new ReflectionProperty($this->reflector->getReference()->target, $name);
    }
}
