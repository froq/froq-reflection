<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An extended `ReflectionObject` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionObject
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionObject extends \ReflectionObject
{
    use internal\trait\ClassTrait;

    /**
     * Constructor.
     *
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->reference = $object;

        parent::__construct($object);
    }
}
