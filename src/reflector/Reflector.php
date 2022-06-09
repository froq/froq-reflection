<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\reflector;

/**
 * Base reflector class.
 *
 * @package froq\reflection\reflector
 * @object  froq\reflection\reflector\Reflector
 * @author  Kerem Güneş
 * @since   6.0
 */
abstract class Reflector
{
    /**
     * Class, method, function, parameter, property and class constant reflection.
     *
     * @var Reflector
     */
    protected \Reflector $reflector;

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
