<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\{DocumentTrait, ReferenceTrait, CallableTrait};
use froq\reflection\internal\reference\CallableReference;

/**
 * An extended `ReflectionMethod` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionMethod
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 */
class ReflectionMethod extends \ReflectionMethod
{
    use DocumentTrait, ReferenceTrait, CallableTrait;

    /**
     * Constructor.
     *
     * @param string|object $classOrObjectOrMethod
     * @param string|null   $method
     */
    public function __construct(string|object $classOrObjectOrMethod, string $method = null)
    {
        parent::__construct($classOrObjectOrMethod, $method);

        $reflection = new \ReflectionMethod($classOrObjectOrMethod, $method);

        $this->setReference(new CallableReference(
            callable   : [$reflection->class, $reflection->name],
            reflection : $reflection
        ));
    }
}
