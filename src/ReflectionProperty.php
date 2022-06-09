<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

use froq\reflection\reflector\{TraitReflector, AttributeReflector};
use Set;

/**
 * An extended `ReflectionProperty` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionProperty
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionProperty extends \ReflectionProperty
{
    /** Property reference */
    private object $reference;

    /**
     * Constructor.
     *
     * @param string|object $class
     * @param string        $name
     */
    public function __construct(string|object $class, string $name)
    {
        parent::__construct($class, $name);

        $this->reference = (object) ['name' => $name, 'class' => $class];
    }

    /** @magic */
    public function __debugInfo(): array
    {
        return ['name' => $this->name, 'class' => $this->class,
                'type' => $this->getType()?->getName(), 'static' => $this->isStatic()];
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /** @override */
    public function getDeclaringClass(): ReflectionClass
    {
        return new ReflectionClass(parent::getDeclaringClass()->name);
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
     * Get traits.
     *
     * @return array<froq\reflection\ReflectionTrait>
     */
    public function getTraits(): array
    {
        return (new TraitReflector($this))->getTraits();
    }

    /**
     * Get trait.
     *
     * @param  string|null $name
     * @return froq\reflection\ReflectionTrait|null
     */
    public function getTrait(string $name = null): ReflectionTrait|null
    {
        return (new TraitReflector($this))->getTrait($name);
    }

    /**
     * Get trait name.
     *
     * @return string|null
     */
    public function getTraitName(): string|null
    {
        return (new TraitReflector($this))->getTrait()?->name;
    }

    /**
     * Get trait names.
     *
     * @return array<string>
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
     * @return array
     */
    public function getAttributeNames(): array
    {
        return (new AttributeReflector($this))->getAttributeNames();
    }

    /** @override */
    public function setValue(mixed $object, mixed $value = null): void
    {
        // Swap for value-only calls.
        if (func_num_args() == 1) {
            [$value, $object] = [$object, null];
        }

        // Permissive (to "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->class)) {
            $object = $this->reference->class;
        }

        if (!is_object($object)) {
            throw new \ReflectionException(sprintf(
                'Cannot set property $%s of non-instantiated class %s',
                $this->reference->name, get_class_name($this->reference->class)
            ));
        }

        parent::setValue($object, $value);
    }

    /** @override */
    public function getValue(object $object = null): mixed
    {
        // Permissive (to "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->class)) {
            $object = $this->reference->class;
        }

        if (!is_object($object)) {
            throw new \ReflectionException(sprintf(
                'Cannot set property $%s of non-instantiated class %s',
                $this->reference->name, get_class_name($this->reference->class)
            ));
        }

        // Prevent "uninitialized" suck.
        if (!$this->isInitialized($object)) {
            return null;
        }

        return parent::getValue($object);
    }

    /** @override */
    public function getType(): ReflectionType|null
    {
        if ($type = parent::getType()) {
            return new ReflectionType(
                ($type instanceof \ReflectionNamedType)
                    ? $type->getName() : (string) $type,
                $type->allowsNull()
            );
        }
        return null;
    }

    /**
     * Get visibility.
     *
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->isPublic() ? 'public' : ($this->isPrivate() ? 'private' : 'protected');
    }

    /**
     * Get modifier names.
     *
     * @return array
     */
    public function getModifierNames(): array
    {
        return \Reflection::getModifierNames($this->getModifiers());
    }

    /**
     * Check whether property is nullable.
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return (bool) $this->getType()?->isNullable();
    }

    /**
     * Check whether property is dynamic.
     *
     * @return bool
     */
    public function isDynamic(): bool
    {
        $name = $this->reference->name;
        $class = $this->reference->class;

        if (!is_object($class) || !property_exists($class, $name)) {
            return false;
        }

        return !array_key_exists($name, get_class_vars(get_class_name($class)));
    }

    /** @override */
    public function isInitialized(object $object = null): bool
    {
        // Permissive (to "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->class)) {
            $object = $this->reference->class;
        }

        return parent::isInitialized($object);
    }
}
