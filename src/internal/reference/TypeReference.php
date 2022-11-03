<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reference;

/**
 * Type reference holder.
 *
 * @package froq\reflection\internal\reference
 * @class   froq\reflection\internal\reference\TypeReference
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
class TypeReference extends Reference
{
    /**
     * Constructor.
     *
     * @param XString  $name
     * @param XArray   $names
     * @param bool     $nullable
     */
    public function __construct(
        public readonly \XString $name,
        public readonly \XArray  $names,
        public readonly bool     $nullable
    )
    {}
}
