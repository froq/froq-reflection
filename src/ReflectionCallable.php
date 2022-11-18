<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait, CallableTrait};
use froq\reflection\internal\reference\CallableReference;

/**
 * A reflection class, combines `ReflectionMethod` & `ReflectionFunction` as
 * one and adds some other utility methods.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionCallable
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 */
class ReflectionCallable implements \Reflector
{
    use DocumentTrait, ReferenceTrait, CallableTrait;

    /**
     * Constructor.
     *
     * @param  string|array|object|callable $classOrObjectOrMethodOrCallable
     * @param  string|null                  $method
     */
    public function __construct(string|array|object|callable $classOrObjectOrMethodOrCallable, string $method = null)
    {
        if (
            // When "Foo::bar" given as single parameter.
            func_num_args() === 1 && is_string($classOrObjectOrMethodOrCallable)
            && preg_match('~(.+)::(\w+)~', $classOrObjectOrMethodOrCallable, $match)
        ) {
            $callable = array_slice($match, 1);
        } elseif (
            // Class / object method.
            $method !== null && (
                is_string($classOrObjectOrMethodOrCallable)
                || is_object($classOrObjectOrMethodOrCallable)
            )
        ) {
            $callable = [$classOrObjectOrMethodOrCallable, $method];
        } else {
            // Closure / function.
            $callable = $classOrObjectOrMethodOrCallable;
        }

        if (is_array($callable)) {
            $reflection = new \ReflectionMethod(...$callable);
            $callable   = [$reflection->class, $reflection->name];
        } else {
            $reflection = new \ReflectionFunction($callable);
        }

        $this->reference = new CallableReference(
            callable   : $callable,
            reflection : $reflection
        );
    }

    /**
     * Proxy for reflection object properties.
     *
     * @param  string $property
     * @return string
     * @throws ReflectionException
     * @magic
     */
    public function __get(string $property): string
    {
        // For name, class actually.
        if (property_exists($this->reference->reflection, $property)) {
            return $this->reference->reflection->$property;
        }

        throw new \ReflectionException(sprintf(
            'Undefined property %s::$%s / %s::$%s',
            $this::class, $property, $this->reference->reflection::class, $property
        ));
    }

    /**
     * Proxy for reflection object methods.
     *
     * @param  string $method
     * @param  array  $methodArgs
     * @return mixed
     * @throws ReflectionException
     * @magic
     */
    public function __call(string $method, array $methodArgs): mixed
    {
        // For all parent methods actually.
        if (method_exists($this->reference->reflection, $method)) {
            return $this->reference->reflection->$method(...$methodArgs);
        }

        throw new \ReflectionException(sprintf(
            'Undefined method %s::%s() / %s::%s()',
            $this::class, $method, $this->reference->reflection::class, $method
        ));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        return $this->reference->reflection->__toString();
    }

    /**
     * Check whether this is a method reflection.
     *
     * @return bool
     */
    public function isMethod(): bool
    {
        return ($this->reference->reflection instanceof \ReflectionMethod);
    }

    /**
     * Check whether this is a function reflection.
     *
     * @return bool
     */
    public function isFunction(): bool
    {
        return ($this->reference->reflection instanceof \ReflectionFunction);
    }
}
