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
 * @class   froq\reflection\ReflectionObject
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

    /**
     * Init a class with/without constructor.
     *
     * @param  mixed ...$arguments
     * @return object
     */
    public function init(mixed ...$arguments): object
    {
        $this->isClass() || throw new \ReflectionException(sprintf(
            'Cannot initialize %s %s', $this->getType(), $this->getName()
        ));

        $ref = new \ReflectionObject($this->reference->target);

        return ($arguments && $ref->hasMethod('__construct'))
             ? $ref->newInstance(...$arguments) : $ref->newInstanceWithoutConstructor();
    }
}
