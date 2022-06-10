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
     * Get visibility.
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
}
