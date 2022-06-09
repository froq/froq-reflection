<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\trait;

use froq\reflection\{ReflectionClass, ReflectionMethod, ReflectionProperty,
    ReflectionAttribute, ReflectionInterface, ReflectionTrait};
use froq\reflection\reflector\{ParentReflector, MethodReflector, PropertyReflector,
    AttributeReflector, InterfaceReflector, TraitReflector};
use froq\util\Objects;
use Set;

/**
 * An internal trait, used by `ReflectionClass` and `ReflectionObject` classes.
 *
 * @package froq\reflection\trait
 * @object  froq\reflection\trait\ClassTrait
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 * @internal
 */
trait ClassTrait
{
    /** Class/object reference. */
    public string|object $reference;

    /** @magic */
    public function __debugInfo(): array
    {
        return ['name' => $this->name];
    }

    /**
     * Check whether this is a class.
     *
     * @return bool
     * @missing
     */
    public function isClass(): bool
    {
        // Enums also considered as class & object.
        return !$this->isInterface() && !$this->isTrait() && !$this->isEnum();
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string
    {
        return Objects::getType($this->reference);
    }

    /**
     * Get namespace.
     *
     * @param  bool $baseOnly
     * @return string
     */
    public function getNamespace(bool $baseOnly = false): string
    {
        return Objects::getNamespace($this->reference, $baseOnly);
    }

    /**
     * Set of parents.
     *
     * @return Set<froq\reflection\ReflectionClass>
     */
    public function parents(): Set
    {
        return (new ParentReflector($this))->parents();
    }

    /**
     * Get parent.
     *
     * @return froq\reflection\ReflectionClass|null
     */
    public function getParent(): ReflectionClass|null
    {
        return (new ParentReflector($this))->getParent();
    }

    /**
     * Get parents.
     *
     * @return array<froq\reflection\ReflectionClass>
     */
    public function getParents(): array
    {
        return (new ParentReflector($this))->getParents();
    }

    /**
     * Get parent name.
     *
     * @return string
     */
    public function getParentName(): string
    {
        return (new ParentReflector($this))->getParentName();
    }

    /**
     * Get parent class names.
     *
     * @return array<string>
     */
    public function getParentNames(): array
    {
        return (new ParentReflector($this))->getParentNames();
    }

    /**
     * Get parent class.
     *
     * @return froq\reflection\ReflectionClass
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getParentClass(): ReflectionClass|null
    {
        return $this->getParent();
    }

    /**
     * @alias getParents()
     */
    public function getParentClasses(): array
    {
        return $this->getParents();
    }

    /**
     * Set of interfaces.
     *
     * @return Set<froq\reflection\ReflectionInterface>
     */
    public function interfaces(): Set
    {
        return (new InterfaceReflector($this))->interfaces();
    }

    /**
     * Get interface.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionInterface|null
     */
    public function getInterface(string $name): ReflectionInterface|null
    {
        return (new InterfaceReflector($this))->getInterface($name);
    }

    /**
     * Get interfaces.
     *
     * @return array<froq\reflection\ReflectionInterface>
     * @override
     */
    public function getInterfaces(): array
    {
        return (new InterfaceReflector($this))->getInterfaces();
    }

    /**
     * Get interface names.
     *
     * @return array<string>
     * @override
     */
    public function getInterfaceNames(): array
    {
        return (new InterfaceReflector($this))->getInterfaceNames();
    }

    /**
     * Set of traits.
     *
     * @return Set<froq\reflection\ReflectionTrait>
     */
    public function traits(): Set
    {
        return (new TraitReflector($this))->traits();
    }

    /**
     * Get trait.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionTrait|null
     */
    public function getTrait(string $name): ReflectionTrait|null
    {
        return (new TraitReflector($this))->getTrait($name);
    }

    /**
     * Get traits.
     *
     * @return array<froq\reflection\ReflectionTrait>
     * @override
     */
    public function getTraits(): array
    {
        return (new TraitReflector($this))->getTraits();
    }

    /**
     * Get trait names.
     *
     * @return array<string>
     * @override
     */
    public function getTraitNames(): array
    {
        return (new TraitReflector($this))->getTraitNames();
    }

    /**
     * Set of attributes.
     *
     * @return Set<ReflectionAttribute>
     */
    public function attributes(): Set
    {
        return (new AttributeReflector($this))->attributes();
    }

    /**
     * Has attribute.
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return (new AttributeReflector($this))->hasAttribute($name);
    }

    /**
     * Get attribute.
     *
     * @param  string $name
     * @return ReflectionAttribute|null
     */
    public function getAttribute(string $name): \ReflectionAttribute|null
    {
        return (new AttributeReflector($this))->getAttribute($name);
    }

    /**
     * Get attribute names.
     *
     * @return array<string>
     */
    public function getAttributeNames(): array
    {
        return (new AttributeReflector($this))->getAttributeNames();
    }

    /**
     * Set of methods.
     *
     * @return Set<froq\reflection\ReflectionMethod>
     */
    public function methods(): Set
    {
        return (new MethodReflector($this))->methods();
    }

    /**
     * Get method.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionMethod
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getMethod(string $name): ReflectionMethod|null
    {
        return (new MethodReflector($this))->getMethod($name);
    }

    /**
     * Get methods.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionMethod>
     * @override
     */
    public function getMethods(int $filter = null): array
    {
        return (new MethodReflector($this))->getMethods($filter);
    }

    /**
     * Get method names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getMethodNames(int $filter = null): array
    {
        return (new MethodReflector($this))->getMethodNames($filter);
    }

    /**
     * Set of properties.
     *
     * @return Set<froq\reflection\ReflectionProperty>
     */
    public function properties(): Set
    {
        return (new PropertyReflector($this))->properties();
    }

    /**
     * Check property.
     *
     * @param  string $name
     * @return bool
     * @override
     */
    public function hasProperty(string $name): bool
    {
        return (new PropertyReflector($this))->hasProperty($name);
    }

    /**
     * Get property.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionProperty
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getProperty(string $name): ReflectionProperty|null
    {
        return (new PropertyReflector($this))->getProperty($name);
    }

    /**
     * Get property.
     *
     * @param  int $filter
     * @return array<froq\reflection\ReflectionProperty>
     * @override
     */
    public function getProperties(int $filter = null): array
    {
        return (new PropertyReflector($this))->getProperties($filter);
    }

    /**
     * Get property names.
     *
     * @param  int|null $filter
     * @return array<string>
     */
    public function getPropertyNames(int $filter = null): array
    {
        return (new PropertyReflector($this))->getPropertyNames($filter);
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
        return (new PropertyReflector($this))->getPropertyValues($filter, $assoc);
    }
}
