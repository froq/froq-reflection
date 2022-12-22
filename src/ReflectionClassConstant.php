<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\DocumentTrait;
use froq\reflection\internal\reflector\{AttributeReflector, InterfaceReflector, TraitReflector};
use Set;

/**
 * An extended `ReflectionClassConstant` class.
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionClassConstant
 * @author  Kerem Güneş
 * @since   6.0
 */
class ReflectionClassConstant extends \ReflectionClassConstant
{
    use DocumentTrait;

    /**
     * Constructor.
     *
     * @param string|object $classOrObjectOrConstant
     * @param string|null   $constant
     */
    public function __construct(string|object $classOrObjectOrConstant, string $constant = null)
    {
        if (
            // When "Foo::BAR" given as single parameter.
            func_num_args() === 1 && is_string($classOrObjectOrConstant)
            && preg_match('~(.+)::(\w+)~', $classOrObjectOrConstant, $match)
        ) {
            [$classOrObjectOrConstant, $constant] = array_slice($match, 1);
        }

        parent::__construct($classOrObjectOrConstant, $constant);
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
     * @return froq\reflection\{ReflectionClass|ReflectionTrait|ReflectionInterface}
     * @override
     */
    public function getDeclaringClass(): ReflectionClass|ReflectionTrait|ReflectionInterface
    {
        $ref = parent::getDeclaringClass();

        return match (true) {
            default => new ReflectionClass($ref->name),
            $ref->isTrait() => new ReflectionTrait($ref->name),
            $ref->isInterface() => new ReflectionInterface($ref->name),
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
     * @param  string|null $name
     * @return froq\reflection\ReflectionInterface|null
     */
    public function getInterface(string $name = null): ReflectionInterface|null
    {
        return (new InterfaceReflector($this))->getInterface($name);
    }

    /**
     * Get interfaces.
     *
     * @return array<froq\reflection\ReflectionInterface>
     */
    public function getInterfaces(): array
    {
        return (new InterfaceReflector($this))->getInterfaces();
    }

    /**
     * Get interface name.
     *
     * @return string|null
     */
    public function getInterfaceName(): string|null
    {
        return (new InterfaceReflector($this))->getInterface()?->name;
    }

    /**
     * Get interface names.
     *
     * @return array<string>
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
     * Get type.
     *
     * @return froq\reflection\ReflectionType
     */
    public function getType(): ReflectionType
    {
        return ReflectionType::of($this->getValue());
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
     * @return array<string>
     */
    public function getModifierNames(): array
    {
        return Reflection::getModifierNames($this->getModifiers());
    }
}
