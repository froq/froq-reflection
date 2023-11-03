<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reference;

/**
 * Attribute reference holder class.
 *
 * @package froq\reflection\internal\reference
 * @class   froq\reflection\internal\reference\AttributeReference
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
class AttributeReference extends Reference
{
    /**
     * Constructor.
     *
     * @param ReflectionAttribute $attribute
     * @param array               $arguments
     */
    public function __construct(
        public readonly \ReflectionAttribute $attribute,
        public readonly array                $arguments
    )
    {}
}
