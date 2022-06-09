<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\reflector;

use froq\reflection\ReflectionProperty;

/**
 * Property reflector class.
 *
 * @package froq\reflection\reflector
 * @object  froq\reflection\reflector\PropertyReflector
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
    public function properties(): \Set
    {
        return (new \Set($this->collect()))
            ->map(fn($name) => new ReflectionProperty(
                $this->reflector->reference, $name
            ));
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
            new \ReflectionProperty($this->reflector->reference, $name);
            return true;
        } catch (\Throwable) {
            return false;
        };
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
            return new ReflectionProperty($this->reflector->reference, $name);
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
        return array_map(
            fn($name) => new ReflectionProperty($this->reflector->reference, $name),
            $this->collect($filter),
        );
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
        $values = array_map(
            fn($name) => (new ReflectionProperty($this->reflector->reference, $name))->getValue(),
            $names = $this->collect($filter)
        );

        return $assoc ? array_combine($names, $values) : $values;
    }

    /**
     * Collect properties.
     */
    private function collect(int $filter = null): array
    {
        $ret = [];
        $ref = new \ReflectionClass($this->reflector->reference);

        foreach ($ref->getProperties($filter) as $property) {
            $ret[$property->name] = $property->name;
        }

        // If no public vanted, skip dynamics.
        if ($filter && !($filter & ReflectionProperty::IS_PUBLIC)) {
            return array_values($ret);
        }

        // Dynamic properties.
        if (is_object($this->reflector->reference)) {
            foreach (array_keys(get_object_vars($this->reflector->reference)) as $var) {
                $ret[$var] ??= $var;
            }
        }

        return array_values($ret);
    }
}
