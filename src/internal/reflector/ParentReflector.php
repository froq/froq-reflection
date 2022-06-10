<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

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
        return (new Set($this->getParentNames()))
            ->map(fn($name) => new ReflectionClass($name));
    }

    /**
     * Get parent.
     *
     * @return froq\reflection\ReflectionClass|null
     */
    public function getParent(): ReflectionClass|null
    {
        $ret = $this->getParentName();

        return $ret ? new ReflectionClass($ret) : null;
    }

    /**
     * Get parents.
     *
     * @return array<froq\reflection\ReflectionClass>
     */
    public function getParents(): array
    {
        $ret = $this->getParentNames();

        return $ret ? array_map(fn($name) => new ReflectionClass($name), $ret) : $ret;
    }

    /**
     * Get parent name.
     *
     * @return string
     */
    public function getParentName(): string
    {
        return (string) Objects::getParent($this->reflector->name);
    }

    /**
     * Get parent names.
     *
     * @return array<string>
     */
    public function getParentNames(): array
    {
        return (array) Objects::getParents($this->reflector->name);
    }
}
