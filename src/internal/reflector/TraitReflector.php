<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

use froq\reflection\{ReflectionTrait, ReflectionCallable};
use froq\util\Objects;
use Set;

/**
 * Trait reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\TraitReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class TraitReflector extends Reflector
{
    /**
     * Set of traits.
     *
     * @return Set<froq\reflection\ReflectionTrait>
     */
    public function traits(): Set
    {
        return new Set($this->getTraits());
    }

    /**
     * Get trait.
     *
     * @param  string|null $name
     * @return froq\reflection\ReflectionTrait|null
     */
    public function getTrait(string $name = null): ReflectionTrait|null
    {
        $names  = $this->getTraitNames();
        $name ??= end($names); // For non-class reflections.

        if ($name && in_array($name, $names, true)) {
            return $this->convert($name);
        }
        return null;
    }

    /**
     * Get traits.
     *
     * @return array<froq\reflection\ReflectionTrait>
     */
    public function getTraits(): array
    {
        return array_apply(
            $this->collect(),
            fn(string|ReflectionTrait $item): ReflectionTrait
                => is_string($item) ? $this->convert($item) : $item,
        );
    }

    /**
     * Get trait names.
     *
     * @return array<string>
     */
    public function getTraitNames(): array
    {
        return array_apply(
            $this->collect(),
            fn(string|ReflectionTrait $item): string
                => is_string($item) ? $item : $item->name
        );
    }

    /**
     * Collect traits for class & method & property reflections.
     */
    private function collect(): array
    {

        if ($this->reflector instanceof \ReflectionClass
            || $this->reflector instanceof \ReflectionObject) {
            return Objects::getTraits($this->reflector->name, all: true);
        }

        $ret = [];

        if ($this->reflector instanceof \ReflectionMethod
            || $this->reflector instanceof ReflectionCallable) {
            $ret = array_filter(
                $this->reflector->getDeclaringClass()->getTraits(),
                fn(ReflectionTrait $ref): bool => (
                    $ref->hasMethod($this->reflector->name) &&
                    $ref->getMethod($this->reflector->name)->class === $ref->name
                )
            );
        } elseif ($this->reflector instanceof \ReflectionProperty) {
            $ret = array_filter(
                $this->reflector->getDeclaringClass()->getTraits(),
                fn(ReflectionTrait $ref): bool => (
                    $ref->hasProperty($this->reflector->name) &&
                    $ref->getProperty($this->reflector->name)->class === $ref->name
                )
            );
        }

        return array_list($ret);
    }

    /**
     * Convert traits to instances.
     */
    private function convert(string $name): ReflectionTrait
    {
        return new ReflectionTrait($name);
    }
}
