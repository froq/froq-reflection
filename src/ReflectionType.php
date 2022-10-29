<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An reflection class, combines `ReflectionNamedType`, `ReflectionUnionType`
 * and `ReflectionIntersectionType` as one and adds some other utility methods.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionType
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionType extends \ReflectionType implements \Reflector
{
    /** Name/nullable reference. */
    public object $reference;

    /** Typing delimiter. */
    private string $delimiter;

    /**
     * Constructor.
     *
     * @param  string $name
     * @param  bool   $nullable
     * @throws ReflectionException
     */
    public function __construct(string $name, bool $nullable = false)
    {
        $name || throw new \ReflectionException('No name given');

        // Null/mixed is nullable.
        if ($name == 'null' || $name == 'mixed') {
            $nullable = true;
        }

        // Uniform nullable types.
        if ($name[0] == '?') {
            $name = substr($name, 1);
            $nullable = true;
        }

        // Place null to the end.
        if ($nullable && ($name != 'null' && $name != 'mixed')) {
            $name .= '|null';
        }

        $this->delimiter = str_contains($name, '&') ? '&' : '|';

        $name = implode($this->delimiter,
            $names = array_unique(explode($this->delimiter, $name)));

        // @tome: Intersection-types don't allow nulls.
        if ($this->delimiter == '|' && in_array('null', $names, true)) {
            $nullable = true;
        }

        $this->reference = (object) ['name' => $name, 'nullable' => $nullable];
    }

    /**
     * Proxy for reference object properties.
     *
     * @param  string $property
     * @return string|bool
     * @throws ReflectionException
     * @magic
     */
    public function __get(string $property): string|bool
    {
        if (isset($this->reference->$property)) {
            return $this->reference->$property;
        }

        throw new \ReflectionException(sprintf(
            'Undefined property %s::$%s', $this::class, $property
        ));
    }

    /** @magic */
    public function __debugInfo(): array
    {
        return ['name' => $this->reference->name,
                'nullable' => $this->reference->nullable];
    }

    /** @magic */
    public function __toString(): string
    {
        return $this->reference->name;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->reference->name;
    }

    /**
     * Get pure name if named (without "null" part if nullable).
     *
     * @return string|null
     */
    public function getPureName(): string|null
    {
        return $this->isNamed() ? $this->getNames()[0] : null;
    }

    /**
     * Get names.
     *
     * @return array
     */
    public function getNames(): array
    {
        return explode($this->delimiter, $this->reference->name);
    }

    /**
     * Get types.
     *
     * @return array<froq\reflection\ReflectionType>
     */
    public function getTypes(): array
    {
        return array_map(fn($name) => new ReflectionType($name), $this->getNames());
    }

    /**
     * Check whether type is builtin.
     *
     * @return bool
     */
    public function isBuiltin(): bool
    {
        return preg_test('~^(int|float|string|bool|array|object|callable|iterable|mixed)(\|null)?$~',
            $this->getName());
    }

    /**
     * Check whether type is named-type.
     *
     * @return bool
     */
    public function isNamed(): bool
    {
        return !$this->isUnion() && !$this->isIntersection();
    }

    /**
     * Check whether type is union-type.
     *
     * @return bool
     */
    public function isUnion(): bool
    {
        return substr_count($this->getName(), '|') >= 2;
    }

    /**
     * Check whether type is intersection-type.
     *
     * @return bool
     */
    public function isIntersection(): bool
    {
        return substr_count($this->getName(), '&') >= 1;
    }

    /**
     * Check whether type is nullable.
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->reference->nullable;
    }

    /**
     * @alias isNullable()
     */
    public function allowsNull(): bool
    {
        return $this->reference->nullable;
    }

    /**
     * Check whether contains given type name.
     *
     * @param  string $name
     * @return bool
     */
    public function contains(string $name): bool
    {
        return in_array($name, $this->getNames(), true);
    }

    /**
     * Static initializer for var types.
     *
     * @param  mixed $var
     * @return froq\reflection\ReflectionType
     */
    public static function of(mixed $var): ReflectionType
    {
        return new ReflectionType(get_type($var));
    }

    /**
     * Static initializer for ReflectionType types.
     *
     * @param  ReflectionType $type
     * @return froq\reflection\ReflectionType
     */
    public static function from(\ReflectionType $type): ReflectionType
    {
        return new ReflectionType(
            ($type instanceof \ReflectionNamedType)
                ? $type->getName() : (string) $type,
            $type->allowsNull()
        );
    }
}
