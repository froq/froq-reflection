<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\ReferenceTrait;
use froq\reflection\internal\reference\TypeReference;
use froq\util\Objects;

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
    use ReferenceTrait;

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

        $name = xstring($name);

        // Null/mixed is nullable.
        if ($name->equals(['null', 'mixed'], icase: true)) {
            $nullable = true;
        }

        // Uniform nullable types.
        if ($name->startsWith('?')) {
            $name->slice(1);
            $nullable = true;
        }

        // Place null to the end.
        if ($nullable && !$name->equals(['null', 'mixed'], icase: true)) {
            $name->append('|null');
        }

        $delimiter = $name->contains('|') ? '|' : '&';

        $names = $name->xsplit($delimiter)->unique();
        $name  = $names->xjoin($delimiter);

        // @tome: Intersection-types don't allow nulls.
        if ($delimiter === '|' && $names->contains('null')) {
            $nullable = true;
        }

        $this->setReference(new TypeReference(
            name     : $name,
            names    : $names,
            nullable : $nullable
        ));
    }

    /**
     * Proxy for reference properties.
     *
     * @param  string $property
     * @return mixed
     * @throws ReflectionException
     * @magic
     */
    public function __get(string $property): mixed
    {
        switch ($property) {
            case 'name':
                return $this->getName();
            case 'names':
                return $this->getNames();
            case 'nullable':
                return $this->isNullable();
            default:
                throw new \ReflectionException(sprintf(
                    'Undefined property %s::$%s', $this::class, $property
                ));
        }
    }

    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        return [
            'name'     => $this->getName(),
            'nullable' => $this->isNullable()
        ];
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->reference->name->toString();
    }

    /**
     * Get pure name if named (without "null" part if nullable).
     *
     * @return string|null
     */
    public function getPureName(): string|null
    {
        return $this->isNamed() ? $this->reference->names[0] : null;
    }

    /**
     * Get short name if class (without "namespace" part).
     *
     * @return string|null
     */
    public function getShortName(): string|null
    {
        return $this->isClass() ? Objects::getShortName($this->reference->names[0]) : null;
    }

    /**
     * Get names.
     *
     * @return array
     */
    public function getNames(): array
    {
        return $this->reference->names->toArray();
    }

    /**
     * Get types.
     *
     * @return array<froq\reflection\ReflectionType>
     */
    public function getTypes(): array
    {
        return $this->reference->names->copy()
            ->apply(fn(string $name): ReflectionType => new ReflectionType($name))
            ->toArray();
    }

    /**
     * Check whether type is named-type.
     *
     * @return bool
     */
    public function isNamed(): bool
    {
        return ($count = $this->count()) === 1 || ($count === 2 && $this->isNullable());
    }

    /**
     * Check whether type is union-type.
     *
     * @return bool
     */
    public function isUnion(): bool
    {
        return !$this->isNamed() && $this->reference->name->contains('|');
    }

    /**
     * Check whether type is intersection-type.
     *
     * @return bool
     */
    public function isIntersection(): bool
    {
        return !$this->isNamed() && $this->reference->name->contains('&');
    }

    /**
     * Check whether type is single (eg: only `int`, but not `int|null`).
     *
     * @return bool
     */
    public function isSingle(): bool
    {
        return $this->count() === 1;
    }

    /**
     * Check whether type is builtin.
     *
     * @return bool
     */
    public function isBuiltin(): bool
    {
        return $this->reference->name->test(
            '~^(int|float|string|bool|array|object|callable|iterable|mixed|true|false|null)(\|null)?$~'
        );
    }

    /**
     * Check whether type is castable via `settype()` function.
     *
     * @return bool
     */
    public function isCastable(): bool
    {
        return $this->reference->name->test(
            '~^(int|float|string|bool|array|object|null)$~'
        );
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
     * Check whether type is an existing class.
     *
     * @return bool
     */
    public function isClass(): bool
    {
        return !$this->isBuiltin() && $this->isNamed();
    }

    /**
     * Check whether type is a type of given class.
     *
     * @param  string $class
     * @return bool
     */
    public function isClassOf(string $class): bool
    {
        return !$this->isBuiltin() && $this->isNamed() && is_class_of($this->getName(), $class);
    }

    /**
     * @alias isNullable()
     * @override
     */
    public function allowsNull(): bool
    {
        return $this->isNullable();
    }

    /**
     * Get count of types.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->reference->names->count();
    }

    /**
     * Get names as Set.
     *
     * @return Set
     */
    public function names(): \Set
    {
        return new \Set($this->getNames());
    }

    /**
     * Get types as Set.
     *
     * @return Set
     */
    public function types(): \Set
    {
        return new \Set($this->getTypes());
    }

    /**
     * Check whether type equals to given type.
     *
     * @param  string $name
     * @return bool
     */
    public function equals(string $name): bool
    {
        return $this->reference->name->equals($name);
    }

    /**
     * Check whether contains given names.
     *
     * @param  string ...$names
     * @return bool
     */
    public function contains(string ...$names): bool
    {
        return $this->reference->names->contains(...$names);
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
