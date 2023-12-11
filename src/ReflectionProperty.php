<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait};
use froq\reflection\internal\reference\PropertyReference;
use froq\reflection\internal\reflector\{AttributeReflector, TraitReflector};
use Set;

/**
 * An extended `ReflectionProperty` class.
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionProperty
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionProperty extends \ReflectionProperty
{
    use DocumentTrait, ReferenceTrait;

    /**
     * Constructor.
     *
     * @param string|object $classOrObjectOrProperty
     * @param string|null   $property
     */
    public function __construct(string|object $classOrObjectOrProperty, string $property = null)
    {
        if (
            // When "Foo::bar" given as single parameter.
            func_num_args() === 1 && is_string($classOrObjectOrProperty)
            && preg_match('~(.+)::(\w+)~', $classOrObjectOrProperty, $match)
        ) {
            [$target, $name] = array_slice($match, 1);
        } else {
            [$target, $name] = [$classOrObjectOrProperty, $property];
        }

        parent::__construct($target, $name);

        $this->reference = new PropertyReference(
            target : $target,
            name   : $name
        );
    }

    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        return ['name' => $this->name, 'class' => $this->class];
    }

    /**
     * Get class.
     *
     * @return string
     * @missing
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Get declaring class.
     *
     * @return froq\reflection\{ReflectionClass|ReflectionTrait}
     * @override
     */
    public function getDeclaringClass(): ReflectionClass|ReflectionTrait
    {
        $ref = parent::getDeclaringClass();

        return match (true) {
            default => new ReflectionClass($ref->name),
            $ref->isTrait() => new ReflectionTrait($ref->name),
        };
    }

    /**
     * Set of attributes.
     *
     * @return Set<froq\reflection\ReflectionAttribute>
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
     * @return froq\reflection\ReflectionAttribute|null
     */
    public function getAttribute(string $name): ReflectionAttribute|null
    {
        return (new AttributeReflector($this))->getAttribute($name);
    }

    /**
     * Get attributes.
     *
     * @param  string|null $name
     * @param  int|null    $flags
     * @return array<froq\reflection\ReflectionAttribute>
     * @override
     */
    public function getAttributes(string $name = null, int $flags = null): array
    {
        return (new AttributeReflector($this))->getAttributes($name, $flags);
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
     * @param  string|null $name
     * @return froq\reflection\ReflectionTrait|null
     */
    public function getTrait(string $name = null): ReflectionTrait|null
    {
        return (new TraitReflector($this))->getTrait($name);
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
     * @throws ReflectionException
     * @override
     */
    public function setValue(mixed $object, mixed $value = null): void
    {
        // Swap for value-only calls.
        if (func_num_args() === 1) {
            [$value, $object] = [$object, null];
        }

        // Permissive (to "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->target)) {
            $object = $this->reference->target;
        }

        if (!is_object($object)) {
            throw new \ReflectionException(sprintf(
                'Cannot set property $%s of non-instantiated class %s',
                $this->reference->name, get_class_name($this->reference->target)
            ));
        }

        parent::setValue($object, $value);
    }

    /**
     * @throws ReflectionException
     * @override
     */
    public function getValue(object $object = null): mixed
    {
        // Permissive (to "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->target)) {
            $object = $this->reference->target;
        }

        if (!is_object($object)) {
            throw new \ReflectionException(sprintf(
                'Cannot get property $%s of non-instantiated class %s',
                $this->reference->name, get_class_name($this->reference->target)
            ));
        }

        // Prevent "uninitialized" suck.
        if (!$this->isInitialized($object)) {
            return null;
        }

        return parent::getValue($object);
    }

    /**
     * @override
     */
    public function getType(): ReflectionType|null
    {
        if ($type = parent::getType()) {
            return ReflectionType::from($type);
        }
        return null;
    }

    /**
     * Get types.
     *
     * @return array<froq\reflection\ReflectionType>
     */
    public function getTypes(): array
    {
        return (array) $this->getType()?->getTypes();
    }

    /**
     * Get visibility.
     *
     * @return string
     */
    public function getVisibility(): string
    {
        return Reflection::getVisibility($this);
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
     * Check if property is nullable.
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        if ($type = $this->getType()) {
            return $type->isNullable();
        }
        return true;
    }

    /**
     * Check if property is dynamic.
     *
     * @return bool
     * @missing
     */
    public function isDynamic(): bool
    {
        return !parent::isDefault();

        // @cancel: Use simply isDefault().
        // $name   = $this->reference->name;
        // $target = $this->reference->target;

        // if (!is_object($target) || !property_exists($target, $name)) {
        //     return false;
        // }

        // return !array_key_exists($name, get_class_vars(get_class_name($target)));
    }

    /**
     * @override
     */
    public function isInitialized(object $object = null): bool
    {
        // Permissive (for "non-object" reflected classes).
        if (!$object && !is_object($this->reference->target)) {
            return parent::hasDefaultValue();
        }

        // Permissive (for "object must be provided for instance properties" error).
        if (!$object && is_object($this->reference->target)) {
            $object = $this->reference->target;
        }

        return parent::isInitialized($object);
    }
}
