<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\trait;

use froq\reflection\{Reflection, ReflectionCallable, ReflectionClass, ReflectionParameter,
    ReflectionInterface, ReflectionTrait, ReflectionAttribute, ReflectionType};
use froq\reflection\internal\reflector\{AttributeReflector, InterfaceReflector, TraitReflector,
    ParameterReflector};
use Set;

/**
 * An internal trait, used by `ReflectionCallable`, `ReflectionMethod` and
 * `ReflectionFunction` classes.
 *
 * @package froq\reflection\internal\trait
 * @class   froq\reflection\internal\trait\CallableTrait
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 * @internal
 */
trait CallableTrait
{
    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        if ($this->reference->reflection instanceof \ReflectionMethod) {
            return ['name'  => $this->reference->reflection->name,
                    'class' => $this->reference->reflection->class];
        }

        return ['name' => $this->reference->reflection->name];
    }

    /**
     * Get class.
     *
     * @return string|null
     */
    public function getClass(): string|null
    {
        if ($this->reference->reflection instanceof \ReflectionMethod) {
            return $this->reference->reflection->class;
        }

        return null;
    }

    /**
     * Get declaring class.
     *
     * @return froq\reflection\{ReflectionClass|ReflectionTrait|ReflectionInterface}|null
     * @override ReflectionMethod.getDeclaringClass()
     */
    #[\ReturnTypeWillChange]
    public function getDeclaringClass(): ReflectionClass|ReflectionTrait|ReflectionInterface|null
    {
        if ($this->reference->reflection instanceof \ReflectionMethod) {
            $ref = $this->reference->reflection->getDeclaringClass();

            return match (true) {
                default => new ReflectionClass($ref->name),
                $ref->isTrait() => new ReflectionTrait($ref->name),
                $ref->isInterface() => new ReflectionInterface($ref->name),
            };
        }

        return null;
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
     * @param  string|array|null $name
     * @param  int|null          $flags
     * @return array<froq\reflection\ReflectionAttribute>
     * @override
     */
    public function getAttributes(string|array $name = null, int $flags = null): array
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
     * Set of parameters.
     *
     * @return Set<froq\reflection\ReflectionParameter>
     */
    public function parameters(): Set
    {
        return (new ParameterReflector($this))->parameters();
    }

    /**
     * Check a parameter by offset (name or position).
     *
     * @param  string|int $offset
     * @return bool
     */
    public function hasParameter(string|int $offset): bool
    {
        return (new ParameterReflector($this))->hasParameter($offset);
    }

    /**
     * Get a parameter by offset (name or position).
     *
     * @param  string|int $offset
     * @return froq\reflection\ReflectionParameter|null
     */
    public function getParameter(string|int $offset): ReflectionParameter|null
    {
        return (new ParameterReflector($this))->getParameter($offset);
    }

    /**
     * Get parameters.
     *
     * @param  array|null $offsets
     * @return array<froq\reflection\ReflectionParameter>
     * @override
     */
    public function getParameters(array $offsets = null): array
    {
        return (new ParameterReflector($this))->getParameters($offsets);
    }

    /**
     * Get parameter names.
     *
     * @return array<string>
     */
    public function getParameterNames(): array
    {
        return (new ParameterReflector($this))->getParameterNames();
    }

    /**
     * Get parameter values.
     *
     * @param  bool $assoc
     * @return array<mixed>
     */
    public function getParameterValues(bool $assoc = false): array
    {
        return (new ParameterReflector($this))->getParameterValues($assoc);
    }

    /**
     * Get parameter values.
     *
     * @param  int|string|array|null $skip
     * @return array
     */
    public function getParameterDefaults(int|string|array $skip = null): array
    {
        $skip = (array) $skip;

        foreach ($this->getParameters() as $i => $parameter) {
            // Skip a name or index of parameter.
            if ($skip && in_array($parameter->name, $skip) || in_array($i, $skip)) {
                continue;
            }

            $ret[$parameter->name] = $parameter->getDefaultValue();
        }

        return $ret ?? [];
    }

    /**
     * Get parameters count.
     *
     * @return int
     */
    public function getParametersCount(): int
    {
        return $this->getNumberOfParameters();
    }

    /**
     * Get required parameters count.
     *
     * @return int
     */
    public function getRequiredParametersCount(): int
    {
        return $this->getNumberOfRequiredParameters();
    }

    /**
     * Get visibility.
     *
     * @return string
     */
    public function getVisibility(): string
    {
        return Reflection::getVisibility($this->reference->reflection);
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
     * @override
     */
    public function getReturnType(): ReflectionType|null
    {
        if ($type = $this->reference->reflection->getReturnType()) {
            return ReflectionType::from($type);
        }
        return null;
    }

    /**
     * Get return types.
     *
     * @return array<froq\reflection\ReflectionType>
     */
    public function getReturnTypes(): array
    {
        return (array) $this->getReturnType()?->getTypes();
    }
}
