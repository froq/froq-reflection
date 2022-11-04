<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionClass;
use froq\util\Objects;
use Set;

/**
 * Parent (class) reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\ParentReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class ParentReflector extends Reflector
{
    /**
     * Set of parents.
     *
     * @return Set<froq\reflection\ReflectionClass>
     */
    public function parents(): Set
    {
        return new Set($this->getParents());
    }

    /**
     * Check parent existence.
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        if ($this->getParentName()) {
            return true;
        }
        return false;
    }

    /**
     * Get parent.
     *
     * @param  bool $baseOnly
     * @return froq\reflection\ReflectionClass|null
     */
    public function getParent(bool $baseOnly = false): ReflectionClass|null
    {
        if ($name = $this->getParentName($baseOnly)) {
            return $this->convert($name);
        }
        return null;
    }

    /**
     * Get parents.
     *
     * @return array<froq\reflection\ReflectionClass>
     */
    public function getParents(): array
    {
        return array_apply(
            $this->getParentNames(),
            fn(string $name): ReflectionClass => $this->convert($name)
        );
    }

    /**
     * Get parent name.
     *
     * @param  bool $baseOnly
     * @return string|null
     */
    public function getParentName(bool $baseOnly = false): string|null
    {
        $name = Objects::getParent($this->reflector->name, $baseOnly);

        // Since get_parent_class() doesn't provide.
        if (!$name && $this->reflector->isInterface()) {
            $names = class_implements($this->reflector->name);
            $names && $name = $baseOnly ? array_last($names) : array_first($names);
        }

        return $name;
    }

    /**
     * Get parent names.
     *
     * @return array<string>
     */
    public function getParentNames(): array
    {
        $names = Objects::getParents($this->reflector->name);

        // Since get_parent_class() doesn't provide.
        if (!$names && $this->reflector->isInterface()) {
            $names = class_implements($this->reflector->name);
            $names && $names = array_list($names);
        }

        return $names;
    }

    /**
     * Convert parents to instances.
     */
    private function convert(string $name): ReflectionClass
    {
        return new ReflectionClass($name);
    }
}
