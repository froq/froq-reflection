<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionClassConstant;
use Set;

/**
 * Class constant reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @class   froq\reflection\internal\reflector\ClassConstantReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class ClassConstantReflector extends Reflector
{
    /**
     * Set of constants.
     *
     * @return Set<froq\reflection\ReflectionClassConstant>
     */
    public function constants(): Set
    {
        return new Set($this->getConstants());
    }

    /**
     * Check constant existence (but not inherit).
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnConstant(string $name): bool
    {
        // Can be declared in an interface or trait.
        if ($this->reflector->name !== $this->getConstant($name)?->getDeclaringClass()->name) {
            return false;
        }
        return true;
    }

    /**
     * Get constant.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionClassConstant|null
     */
    public function getConstant(string $name): ReflectionClassConstant|null
    {
        try {
            return $this->convert($name);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Get constants.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionClassConstant>
     */
    public function getConstants(int $filter = null): array
    {
        return array_apply(
            $this->collect($filter),
            fn($_, string $name): ReflectionClassConstant => $this->convert($name),
            list: true
        );
    }

    /**
     * Get constant names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getConstantNames(int $filter = null): array
    {
        return array_keys($this->collect($filter));
    }

    /**
     * Get constant values.
     *
     * @param  int|null $filter
     * @param  bool     $assoc
     * @return array<mixed>
     */
    public function getConstantValues(int $filter = null, bool $assoc = false): array
    {
        return $assoc ? $this->collect($filter) : array_values($this->collect($filter));
    }

    /**
     * Collect constants.
     */
    private function collect(int $filter = null): array
    {
        $ref = new \ReflectionClass($this->reflector->name);

        return $ref->getConstants($filter);
    }

    /**
     * Convert constants to instances.
     */
    private function convert(string $name): ReflectionClassConstant
    {
        return new ReflectionClassConstant($this->reflector->name, $name);
    }
}
