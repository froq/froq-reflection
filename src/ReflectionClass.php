<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An extended `ReflectionClass` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionClass
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionClass extends \ReflectionClass
{
    use internal\trait\ClassTrait;

    /**
     * Constructor.
     *
     * @param string|object $target
     */
    public function __construct(string|object $target)
    {
        $this->reference = $target;

        parent::__construct($target);
    }
}
