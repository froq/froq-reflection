<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait, ClassTrait};
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
    use DocumentTrait, ReferenceTrait, ClassTrait;

    /**
     * Constructor.
     *
     * @param object $target
     */
    public function __construct(object $target)
    {
        parent::__construct($target);

        $this->reference = new ClassReference(
            target : $target
        );
    }
}
