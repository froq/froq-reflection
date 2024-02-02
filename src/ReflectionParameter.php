<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\ReferenceTrait;
use froq\reflection\internal\reference\ParameterReference;

/**
 * An extended `ReflectionParameter` class.
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionParameter
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionParameter extends \ReflectionParameter
{
    use ReferenceTrait;

    /**
     * Constructor.
     *
     * @param string|array|object $functionOrMethod
     * @param string|int          $nameOrPosition
     */
    public function __construct(string|array|object $functionOrMethod, int|string $nameOrPosition)
    {
        if (
            // When "Foo::bar" given as method.
            is_string($functionOrMethod)
            && preg_match('~(.+)::(\w+)~', $functionOrMethod, $match)
        ) {
            $functionOrMethod = array_slice($match, 1);
        }

        parent::__construct($functionOrMethod, $nameOrPosition);

        $this->reference = new ParameterReference(
            callable : $functionOrMethod
        );
    }

    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        return [
            'name'     => $this->name,
            'function' => $this->reference->name()
        ];
    }

    /**
     * Get class name (if its function is a method).
     *
     * Note: This method is deprecated by the internal reflection API,
     * but we continue to provide it though.
     *
     * @return string|null
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getClass(): string|null
    {
        return parent::getDeclaringClass()?->name;
    }

    /**
     * Get declaring class.
     *
     * @return froq\reflection\ReflectionClass|null
     * @override
     */
    public function getDeclaringClass(): ReflectionClass|null
    {
        $ref = parent::getDeclaringClass();

        if (!$ref) {
            return null;
        }

        return match (true) {
            default => new ReflectionClass($ref->name),
            $ref?->isTrait() => new ReflectionTrait($ref->name),
            $ref?->isInterface() => new ReflectionInterface($ref->name),
        };
    }

    /**
     * Get declaring method (if its function is a method).
     *
     * @return froq\reflection\ReflectionMethod|null
     */
    public function getDeclaringMethod(): ReflectionMethod|null
    {
        if ($this->reference->callable instanceof \Closure) {
            return null;
        }

        $ref = parent::getDeclaringFunction();

        if ($ref instanceof \ReflectionFunction) {
            return null;
        }

        return new ReflectionMethod($ref->class, $ref->name);
    }

    /**
     * Get declaring function.
     *
     * Note: PHP Documents say, "Return Values: A ReflectionFunction object" but returns
     * ReflectionMethod if the function is a Closure and defined in a class method.
     *
     * @return froq\reflection\{ReflectionFunction|ReflectionMethod}
     * @override
     */
    public function getDeclaringFunction(): ReflectionFunction|ReflectionMethod
    {
        if ($this->reference->callable instanceof \Closure) {
            return new ReflectionFunction($this->reference->callable);
        }

        if ($ref = $this->getDeclaringMethod()) {
            return $ref;
        }

        $ref = parent::getDeclaringFunction();

        return new ReflectionFunction($ref->name);
    }

    /**
     * Check default value existence.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        // @cancel: Same results.
        // return $this->isOptional() && !$this->isVariadic();

        return parent::isDefaultValueAvailable();
    }

    /**
     * @override
     */
    public function getDefaultValue(mixed $default = null): mixed
    {
        return $this->hasDefaultValue() ? parent::getDefaultValue() : $default;
    }

    /**
     * @override
     */
    public function isDefaultValueConstant(): bool
    {
        return $this->hasDefaultValue() ? parent::isDefaultValueConstant() : false;
    }

    /**
     * @override
     */
    public function getDefaultValueConstantName(): string|null
    {
        return $this->hasDefaultValue() ? parent::getDefaultValueConstantName() : null;
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
     * @alias allowsNull()
     */
    public function isNullable(): bool
    {
        return $this->allowsNull();
    }
}
