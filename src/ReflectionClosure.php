<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

/**
 * A reflection class for closures (same as ReflectionFunction but displays "class" too).
 *
 * @package froq\reflection
 * @class   froq\reflection\ReflectionClosure
 * @author  Kerem Güneş
 * @since   7.7
 */
class ReflectionClosure extends ReflectionFunction
{
    /** This is just ducking awesome.
     *  It is not possible to define
     *  a property named $class, bravo! */
    public readonly string|null $class_;

    /**
     * Constructor.
     *
     * @param Closure     $target
     * @param string|null $class
     */
    public function __construct(\Closure $target, string $class = null)
    {
        parent::__construct($target);

        $this->class_ = $class ?? parent::getClosureScopeClass()?->name;
    }
}
