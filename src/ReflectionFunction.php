<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait, CallableTrait};
use froq\reflection\internal\reference\CallableReference;

/**
 * An extended `ReflectionFunction` class.
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionFunction
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 */
class ReflectionFunction extends \ReflectionFunction
{
    use DocumentTrait, ReferenceTrait, CallableTrait;

    /**
     * Constructor.
     *
     * @param string|Closure $function
     */
    public function __construct(string|\Closure $function)
    {
        parent::__construct($function);

        $reflection = new \ReflectionFunction($function);

        $this->reference = new CallableReference(
            callable   : $function,
            reflection : $reflection
        );
    }
}
