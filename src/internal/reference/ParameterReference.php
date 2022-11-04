<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reference;

/**
 * Parameter reference holder class.
 *
 * @package froq\reflection\internal\reference
 * @class   froq\reflection\internal\reference\ParameterReference
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
class ParameterReference extends Reference
{
    /**
     * Constructor.
     *
     * @param string|array|object $callable
     */
    public function __construct(
        public readonly string|array|object $callable
    )
    {}

    /**
     * Get callable's name.
     *
     * @return string
     */
    public function name(): string
    {
        switch (true) {
            case is_array($this->callable):
                return join('::', $this->callable);
            case is_string($this->callable):
                return $this->callable;
            default:
                return '{closure}';
        }
    }
}
