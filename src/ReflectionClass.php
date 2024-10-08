<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait, ClassTrait};
use froq\reflection\internal\reference\ClassReference;

/**
 * An extended `ReflectionClass` class.
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionClass
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionClass extends \ReflectionClass
{
    use DocumentTrait, ReferenceTrait, ClassTrait;

    /**
     * Constructor.
     *
     * @param string|object $target
     */
    public function __construct(string|object $target)
    {
        parent::__construct($target);

        $this->reference = new ClassReference(
            target : $target
        );
    }
}
