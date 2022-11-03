<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionMethod;
use Set;

/**
 * Method reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\MethodReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class MethodReflector extends Reflector
{
    /**
     * Set of methods.
     *
     * @return Set<froq\reflection\ReflectionMethod>
     */
    public function methods(): Set
    {
        return (new Set($this->collect()))
            ->map(fn($ref) => $this->convert($ref->name));
    }

    /**
     * Check method existence (but not inherit).
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnMethod(string $name): bool
    {
        // @keep: Why? Cos' return type can change in ClassTrait.getMethod().
        // if (!$this->hasMethod($name)) {
        //     return false;
        // }

        // Can be declared in a super class.
        if ($this->reflector->name !== $this->getMethod($name)?->getDeclaringClass()->name) {
            return false;
        }

        return true;
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
            return $this->convert($name);
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
        return array_map([$this, 'convert'], $this->getMethodNames($filter));
    }

    /**
     * Get method names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getMethodNames(int $filter = null): array
    {
        return array_map(fn($ref) => $ref->name, $this->collect($filter));
    }

    /**
     * Collect methods.
     */
    private function collect(int $filter = null): array
    {
        $ref = new \ReflectionClass($this->reflector->name);

        return $ref->getMethods($filter);
    }

    /**
     * Convert methods to instances.
     */
    private function convert(string $name): ReflectionMethod
    {
        return new ReflectionMethod($this->reflector->name, $name);
    }
}
