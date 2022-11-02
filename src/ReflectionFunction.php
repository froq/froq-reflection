<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{CallableTrait, ReferenceTrait};

/**
 * An extended `ReflectionFunction` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionFunction
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 */
class ReflectionFunction extends \ReflectionFunction
{
    use CallableTrait, ReferenceTrait;

    /**
     * Constructor.
     *
     * @param string|Closure $function
     */
    public function __construct(string|\Closure $function)
    {
        parent::__construct($function);

        // Create internal reflection.
        $reflection = new \ReflectionFunction($function);

        $this->setReference([
            'callable'   => $function,
            'reflection' => $reflection
        ]);
    }
}
