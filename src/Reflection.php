<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

/**
 * Reflection utility class.
 *
 * @package froq\reflection
 * @object  froq\reflection\Reflection
 * @author  Kerem Güneş
 * @since   6.0
 */
class Reflection extends \Reflection
{
    /**
     * Get visibility (for class constants, methods and property reflections).
     *
     * @return Reflector $reflector
     * @return string
     */
    public static function getVisibility(\Reflector $reflector): string
    {
        return match (true) {
            $reflector->isPublic()  => 'public',
            $reflector->isPrivate() => 'private',
            default                 => 'protected'
        };
    }

    /**
     * Shortcut for creating ReflectionCallable instances.
     */
    public static function reflectCallable(...$args): ReflectionCallable
    {
        return new ReflectionCallable(...$args);
    }

    /**
     * Shortcut for creating ReflectionClass instances.
     */
    public static function reflectClass(...$args): ReflectionClass
    {
        return new ReflectionClass(...$args);
    }

    /**
     * Shortcut for creating ReflectionClassConstant instances.
     */
    public static function reflectClassConstant(...$args): ReflectionClassConstant
    {
        return new ReflectionClassConstant(...$args);
    }

    /**
     * Shortcut for creating ReflectionFunction instances.
     */
    public static function reflectFunction(...$args): ReflectionFunction
    {
        return new ReflectionFunction(...$args);
    }

    /**
     * Shortcut for creating ReflectionInterface instances.
     */
    public static function reflectInterface(...$args): ReflectionInterface
    {
        return new ReflectionInterface(...$args);
    }

    /**
     * Shortcut for creating ReflectionMethod instances.
     */
    public static function reflectMethod(...$args): ReflectionMethod
    {
        return new ReflectionMethod(...$args);
    }

    /**
     * Shortcut for creating ReflectionNamespace instances.
     */
    public static function reflectNamespace(...$args): ReflectionNamespace
    {
        return new ReflectionNamespace(...$args);
    }

    /**
     * Shortcut for creating ReflectionObject instances.
     */
    public static function reflectObject(...$args): ReflectionObject
    {
        return new ReflectionObject(...$args);
    }

    /**
     * Shortcut for creating ReflectionParameter instances.
     */
    public static function reflectParameter(...$args): ReflectionParameter
    {
        return new ReflectionParameter(...$args);
    }

    /**
     * Shortcut for creating ReflectionProperty instances.
     */
    public static function reflectProperty(...$args): ReflectionProperty
    {
        return new ReflectionProperty(...$args);
    }

    /**
     * Shortcut for creating ReflectionTrait instances.
     */
    public static function reflectTrait(...$args): ReflectionTrait
    {
        return new ReflectionTrait(...$args);
    }

    /**
     * Shortcut for creating ReflectionType instances.
     */
    public static function reflectType(...$args): ReflectionType
    {
        return new ReflectionType(...$args);
    }
}
