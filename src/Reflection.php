<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

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
     * @return Reflector $ref
     * @return string
     */
    public static function getVisibility(\Reflector $ref): string
    {
        return match (true) {
            $ref->isPublic()  => 'public',
            $ref->isPrivate() => 'private',
            default           => 'protected'
        };
    }
}
