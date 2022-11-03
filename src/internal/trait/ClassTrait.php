<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\trait;

use froq\reflection\{Reflection, ReflectionClass, ReflectionClassConstant, ReflectionProperty, ReflectionMethod,
    ReflectionInterface, ReflectionTrait, ReflectionNamespace};
use froq\reflection\internal\reflector\{AttributeReflector, InterfaceReflector, TraitReflector,
    ParentReflector, ClassConstantReflector, PropertyReflector, MethodReflector};
use froq\util\Objects;
use ReflectionAttribute;
use Set;

/**
 * An internal trait, used by `ReflectionClass` and `ReflectionObject` classes.
 *
 * @package froq\reflection\internal\trait
 * @object  froq\reflection\internal\trait\ClassTrait
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 * @internal
 */
trait ClassTrait
{
    /**
     * @magic
     */
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
     * Check whether this is a clonable class (for typos).
     *
     * @return bool
     */
    public function isClonable(): bool
    {
        return $this->isCloneable();
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string
    {
        return Objects::getType($this->reference->target);
    }

    /**
     * Get namespace.
     *
     * @param  bool $baseOnly
     * @return string
     */
    public function getNamespace(bool $baseOnly = false): string
    {
        return Objects::getNamespace($this->reference->target, $baseOnly);
    }

    /**
     * Get declaring namespace.
     *
     * @return froq\reflection\ReflectionNamespace
     */
    public function getDeclaringNamespace(): ReflectionNamespace
    {
        return new ReflectionNamespace($this->getNamespace());
    }

    /**
     * Get modifier names.
     *
     * @return array
     */
    public function getModifierNames(): array
    {
        return Reflection::getModifierNames($this->getModifiers());
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
     * @param  bool $baseOnly
     * @return froq\reflection\ReflectionClass|null
     */
    public function getParent(bool $baseOnly = false): ReflectionClass|null
    {
        return (new ParentReflector($this))->getParent($baseOnly);
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
     * @param  bool $baseOnly
     * @return string
     */
    public function getParentName(bool $baseOnly = false): string
    {
        return (new ParentReflector($this))->getParentName($baseOnly);
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
     * @param  bool $baseOnly
     * @return froq\reflection\ReflectionClass|null
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getParentClass(bool $baseOnly = false): ReflectionClass|null
    {
        return $this->getParent($baseOnly);
    }

    /**
     * @alias getParents()
     */
    public function getParentClasses(): array
    {
        return $this->getParents();
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
    public function getAttribute(string $name): ReflectionAttribute|null
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
     * Set of methods.
     *
     * @return Set<froq\reflection\ReflectionMethod>
     */
    public function methods(): Set
    {
        return (new MethodReflector($this))->methods();
    }

    /**
     * Has own method.
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnMethod(string $name): bool
    {
        return (new MethodReflector($this))->hasOwnMethod($name);
    }

    /**
     * Get method.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionMethod|null
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
     * Set of constants.
     *
     * @return Set<froq\reflection\ReflectionClassConstant>
     */
    public function constants(): Set
    {
        return (new ClassConstantReflector($this))->constants();
    }

    /**
     * Has own constant.
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnConstant(string $name): bool
    {
        return (new ClassConstantReflector($this))->hasOwnConstant($name);
    }

    /**
     * Get constant.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionClassConstant|null
     * @override
     */
    public function getConstant(string $name): ReflectionClassConstant|null
    {
        return (new ClassConstantReflector($this))->getConstant($name);
    }

    /**
     * Get constants.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionClassConstant>
     * @override
     */
    public function getConstants(int $filter = null): array
    {
        return (new ClassConstantReflector($this))->getConstants($filter);
    }

    /**
     * Get constant names.
     *
     * @param  int|null $filter
     * @return array<string>
     * @missing
     */
    public function getConstantNames(int $filter = null): array
    {
        return (new ClassConstantReflector($this))->getConstantNames($filter);
    }

    /**
     * Get constant values.
     *
     * @param  int|null $filter
     * @param  bool     $assoc
     * @return array<mixed>
     * @missing
     */
    public function getConstantValues(int $filter = null, bool $assoc = false): array
    {
        return (new ClassConstantReflector($this))->getConstantValues($filter, $assoc);
    }

    /**
     * Get reflection constant.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionClassConstant|null
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getReflectionConstant(string $name): ReflectionClassConstant|null
    {
        return $this->getConstant($name);
    }

    /**
     * Get reflection constants.
     *
     * @param  int|null $filter
     * @return array<froq\reflection\ReflectionClassConstant>
     * @override
     */
    public function getReflectionConstants(int $filter = null): array
    {
        return $this->getConstants($filter);
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
     * Has property.
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
     * Has own property.
     *
     * @param  string $name
     * @return bool
     */
    public function hasOwnProperty(string $name): bool
    {
        return (new PropertyReflector($this))->hasOwnProperty($name);
    }

    /**
     * Get property.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionProperty|null
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
     * @param  int|null $filter
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
