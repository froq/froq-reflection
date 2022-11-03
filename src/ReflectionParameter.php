<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An extended `ReflectionParameter` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionParameter
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionParameter extends \ReflectionParameter
{
    /**
     * Constructor.
     *
     * @param string|array|object $functionOrMethod
     * @param string|int          $nameOrPosition
     */
    public function __construct(string|array|object $functionOrMethod, int|string $nameOrPosition)
    {
        if ( // When "Foo::bar" given as method.
            is_string($functionOrMethod)
            && preg_match('~(.+)::(\w+)~', $functionOrMethod, $match)
        ) {
            $functionOrMethod = array_slice($match, 1);
        }

        parent::__construct($functionOrMethod, $nameOrPosition);
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
        $ref = parent::getDeclaringFunction();

        if ($ref && $ref instanceof \ReflectionMethod) {
            return new ReflectionMethod($ref->class, $ref->name);
        }

        return null;
    }

    /**
     * Get declaring function.
     *
     * @return froq\reflection\{ReflectionMethod|ReflectionFunction}|null
     * @override
     */
    #[\ReturnTypeWillChange]
    public function getDeclaringFunction(): ReflectionMethod|ReflectionFunction|null
    {
        if ($ref = $this->getDeclaringMethod()) {
            return $ref;
        }

        if ($ref = parent::getDeclaringFunction()) {
            return new ReflectionFunction($ref->getClosure());
        }

        return null;
    }

    /**
     * Check default value existence.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->isOptional() && !$this->isVariadic();
    }

    /**
     * Get default value return null.
     *
     * @return mixed
     * @override
     */
    public function getDefaultValue(): mixed
    {
        return $this->hasDefaultValue() ? parent::getDefaultValue() : null;
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
