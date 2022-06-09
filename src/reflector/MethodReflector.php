<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\reflector;

use froq\reflection\ReflectionMethod;

/**
 * Method reflector class.
 *
 * @package froq\reflection\reflector
 * @object  froq\reflection\reflector\MethodReflector
 * @author  Kerem Güneş
 * @since   6.0
 */
class MethodReflector extends Reflector
{
    /**
     * Set of methods.
     *
     * @return Set<froq\reflection\ReflectionMethod>
     */
    public function methods(): \Set
    {
        return (new \Set($this->collect()))
            ->map(fn($ref) => new ReflectionMethod($ref->class, $ref->name));
    }

    /**
     * Get method.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionMethod|null
     */
    public function getMethod(string $name): ReflectionMethod|null
    {
        try {
            return new ReflectionMethod($this->reflector->name, $name);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Get methods.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionMethod>
     */
    public function getMethods(int $filter = null): array
    {
        return array_map(
            fn($ref) => new ReflectionMethod($ref->class, $ref->name),
            $this->collect($filter)
        );
    }

    /**
     * Get method names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getMethodNames(int $filter = null): array
    {
        return array_map(
            fn($ref) => $ref->name,
            $this->collect($filter)
        );
    }

    /**
     * Collect methods.
     */
    private function collect(int $filter = null): array
    {
        $ref = new \ReflectionClass($this->reflector->name);

        return $ref->getMethods($filter);
    }
}
