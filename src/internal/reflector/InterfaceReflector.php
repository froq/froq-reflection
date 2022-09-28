<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\reflector;

use froq\reflection\{ReflectionInterface, ReflectionCallable};
use froq\util\Objects;
use Set;

/**
 * Interface reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\InterfaceReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class InterfaceReflector extends Reflector
{
    /**
     * Set of interfaces.
     *
     * @return Set<froq\reflection\ReflectionInterface>
     */
    public function interfaces(): Set
    {
        return (new Set($this->getInterfaceNames()))
            ->map(fn($name) => new ReflectionInterface($name));
    }

    /**
     * Get interface.
     *
     * @param  string|null $name
     * @return froq\reflection\ReflectionInterface|null
     */
    public function getInterface(string $name = null): ReflectionInterface|null
    {
        $names  = $this->getInterfaceNames();
        $name ??= end($names); // For non-class reflections.

        if ($name && in_array($name, $names, true)) {
            return new ReflectionInterface($name);
        }
        return null;
    }

    /**
     * Get interfaces.
     *
     * @return array<froq\reflection\ReflectionInterface>
     */
    public function getInterfaces(): array
    {
        return array_map(
            fn($item) => is_string($item) ? new ReflectionInterface($item) : $item,
            $this->collect()
        );
    }

    /**
     * Get interface names.
     *
     * @return array<string>
     */
    public function getInterfaceNames(): array
    {
        return array_map(
            fn($item) => is_string($item) ? $item : $item->name,
            $this->collect()
        );
    }

    /**
     * Collect interfaces for class & method reflections.
     */
    private function collect(): array
    {
        if ($this->ref instanceof \ReflectionClass
            || $this->ref instanceof \ReflectionObject) {
            return Objects::getInterfaces($this->ref->name);
        }

        $ret = [];

        if ($this->ref instanceof \ReflectionMethod
            || $this->ref instanceof ReflectionCallable) {
            $ret = array_filter(
                $this->ref->getDeclaringClass()->getInterfaces(),
                fn($ref) => (
                    $ref->hasMethod($this->ref->name) &&
                    $ref->getMethod($this->ref->name)->class == $ref->name
                )
            );
        } elseif ($this->ref instanceof \ReflectionClassConstant) {
            $ret = array_filter(
                $this->ref->getDeclaringClass()->getInterfaces(),
                fn($ref) => (
                    $ref->hasConstant($this->ref->name) &&
                    $ref->getReflectionConstant($this->ref->name)->class == $ref->name
                )
            );
        }

        return array_values($ret);
    }
}
