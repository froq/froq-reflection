<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{ClassTrait, ReferenceTrait};
use froq\reflection\internal\reference\ClassReference;

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
    use ClassTrait, ReferenceTrait;

    /**
     * Constructor.
     *
     * @param object $target
     */
    public function __construct(object $target)
    {
        parent::__construct($target);

        $this->setReference(new ClassReference(
            target : $target
        ));
    }
}
