<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reference;

/**
 * Property reference holder class.
 *
 * @package froq\reflection\internal\reference
 * @class   froq\reflection\internal\reference\PropertyReference
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
class PropertyReference extends Reference
{
    /**
     * Constructor.
     *
     * @param string|object $target
     * @param string        $name
     */
    public function __construct(
        public readonly string|object $target,
        public readonly string        $name
    )
    {}
}
