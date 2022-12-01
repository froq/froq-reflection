<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

/**
 * Base reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @class   froq\reflection\internal\reflector\Reflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
abstract class Reflector
{
    /**
     * Class, method, function, parameter, property and class constant reflection.
     */
    protected readonly \Reflector $reflector;

    /**
     * Constructor.
     *
     * @param Reflector $reflector
     */
    public function __construct(\Reflector $reflector)
    {
        $this->reflector = $reflector;
    }
}
