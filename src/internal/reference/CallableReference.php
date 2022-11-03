<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reference;

/**
 * Callable reference holder.
 *
 * @package froq\reflection\internal\reference
 * @class   froq\reflection\internal\reference\CallableReference
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
class CallableReference extends Reference
{
    /**
     * Constructor.
     *
     * @param string|array|object                 $callable
     * @param ReflectionMethod|ReflectionFunction $reflection
     */
    public function __construct(
        public readonly string|array|object                   $callable,
        public readonly \ReflectionMethod|\ReflectionFunction $reflection
    )
    {}
}
