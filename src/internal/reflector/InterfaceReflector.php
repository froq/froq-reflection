<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
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
        return new Set($this->getInterfaces());
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
            return $this->convert($name);
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
        return array_apply(
            $this->collect(),
            fn(string|ReflectionInterface $item): ReflectionInterface
                => is_string($item) ? $this->convert($item) : $item
        );
    }

    /**
     * Get interface names.
     *
     * @return array<string>
     */
    public function getInterfaceNames(): array
    {
        return array_apply(
            $this->collect(),
            fn(string|ReflectionInterface $item): string
                => is_string($item) ? $item : $item->name
        );
    }

    /**
     * Collect interfaces for class & method reflections.
     */
    private function collect(): array
    {
        if ($this->reflector instanceof \ReflectionClass
            || $this->reflector instanceof \ReflectionObject) {
            return Objects::getInterfaces($this->reflector->name);
        }

        $ret = [];

        if ($this->reflector instanceof \ReflectionMethod
            || $this->reflector instanceof ReflectionCallable) {
            $ret = array_filter(
                $this->reflector->getDeclaringClass()->getInterfaces(),
                fn(ReflectionInterface $ref): bool => (
                    $ref->hasMethod($this->reflector->name) &&
                    $ref->getMethod($this->reflector->name)->class === $ref->name
                )
            );
        } elseif ($this->reflector instanceof \ReflectionClassConstant) {
            $ret = array_filter(
                $this->reflector->getDeclaringClass()->getInterfaces(),
                fn(ReflectionInterface $ref): bool => (
                    $ref->hasConstant($this->reflector->name) &&
                    $ref->getReflectionConstant($this->reflector->name)->class === $ref->name
                )
            );
        }

        return array_list($ret);
    }

    /**
     * Convert interfaces to instances.
     */
    private function convert(string $name): ReflectionInterface
    {
        return new ReflectionInterface($name);
    }
}
