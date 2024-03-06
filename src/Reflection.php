<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\util\Objects;

/**
 * Reflection utility class.
 *
 * @package froq\reflection
 * @class   froq\reflection\Reflection
 * @author  Kerem Güneş
 * @since   6.0
 */
class Reflection extends \Reflection
{
    /**
     * Get type of given reflector.
     *
     * @param  Reflector $reflector
     * @return string|null
     */
    public static function getType(\Reflector $reflector): string|null
    {
        if ($reflector instanceof \ReflectionClass) {
            if ($reflector instanceof \ReflectionObject) {
                return 'object';
            }

            // Detect: class, interface, trait or enum.
            return Objects::getType($reflector->getName());
        }

        return match (true) {
            $reflector instanceof ReflectionAttribute,
            $reflector instanceof \ReflectionAttribute     => 'attribute',
            $reflector instanceof ReflectionCallable       => 'callable',
            $reflector instanceof \ReflectionClassConstant => 'class-constant',
            $reflector instanceof ReflectionClosure        => 'closure',
            $reflector instanceof \ReflectionMethod        => 'method',
            $reflector instanceof \ReflectionFunction      => 'function',
            $reflector instanceof ReflectionNamespace      => 'namespace',
            $reflector instanceof \ReflectionParameter     => 'parameter',
            $reflector instanceof \ReflectionProperty      => 'property',
            $reflector instanceof \ReflectionType          => 'type',
            default                                        => null,
        };
    }

    /**
     * Get visibility (for class constant, property, method reflections).
     *
     * @return Reflector $reflector
     * @return string|null
     */
    public static function getVisibility(\Reflector $reflector): string|null
    {
        if ($reflector instanceof \ReflectionClassConstant ||
            $reflector instanceof \ReflectionProperty ||
            $reflector instanceof \ReflectionMethod) {
            return match (true) {
                $reflector->isPublic()  => 'public',
                $reflector->isPrivate() => 'private',
                default                 => 'protected'
            };
        }

        return null;
    }

    /**
     * Reflect (for object, class, class constant, property, method, function, trait, interface
     * and namespace targets).
     *
     * @param  string|object $target
     * @param  string|null   $type
     * @return Reflector|null
     * @throws ReflectionException
     */
    public static function reflect(string|object $target, string $type = null): \Reflector|null
    {
        if ($target instanceof \Closure) {
            return new ReflectionClosure($target);
        }
        if (is_object($target) && $type !== 'attribute') {
            return new ReflectionObject($target);
        }

        $skip = false;

        // Blind tries.
        if ($type === null) {
            if (class_exists($target)) {
                return new ReflectionClass($target);
            }
            if (function_exists($target)) {
                return new ReflectionFunction($target);
            }

            // Eg: Foo@bar or Foo::bar
            if (str_has($target, ['@', '::'])) {
                [$class, $member] = str_pop($target, ['@', '::'], 2);

                if (isset($class, $member)) {
                    // Match by check.
                    $match = match (true) {
                        constant_exists($class, $member) => $type = 'constant',
                        property_exists($class, $member) => $type = 'property',
                        method_exists($class, $member)   => $type = 'method',
                        default                          => null,
                    };

                    $target = join('::', [$class, $member]);
                    $skip   = true;
                }
            }
        }

        // Type tries.
        if ($type !== null && !$skip) {
            switch ($type) {
                case 'class':
                    return new ReflectionClass($target);
                case 'function':
                    return new ReflectionFunction($target);

                case 'trait':
                    return new ReflectionTrait($target);
                case 'interface':
                    return new ReflectionInterface($target);
                case 'namespace':
                    return new ReflectionNamespace($target);

                case 'callable':
                    return new ReflectionCallable($target);
            }
        }

        if ($type !== null) {
            switch ($type) {
                case 'constant':
                case 'class-constant':
                    return new ReflectionClassConstant($target);
                case 'property':
                case 'class-property':
                    return new ReflectionProperty($target);
                case 'method':
                case 'class-method':
                    return new ReflectionMethod($target);
                case 'class-namespace':
                    $target = get_class_namespace($target);
                    return new ReflectionNamespace($target);

                case 'type':
                    return new ReflectionType($target);
                case 'attribute':
                    $target = ($target instanceof ReflectionAttribute)
                        ? $target->reference->attribute : $target;
                    return new ReflectionAttribute($target);
                // Can't do this cos of constructor arguments.
                // case 'parameter':
                //     return new ReflectionParameter($target);

                default:
                    throw new \ReflectionException('Invalid type: ' . $type);
            }
        }

        return null;
    }

    /**
     * Shortcut for creating ReflectionAttribute instances.
     */
    public static function reflectAttribute(...$args): ReflectionAttribute
    {
        return new ReflectionAttribute(...$args);
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
     * Shortcut for creating ReflectionClosure instances.
     */
    public static function reflectClosure(...$args): ReflectionClosure
    {
        return new ReflectionClosure(...$args);
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
