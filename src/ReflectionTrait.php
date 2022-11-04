<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

/**
 * Kind of missing reflection class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionTrait
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionTrait extends ReflectionClass
{
    /**
     * Constructor.
     *
     * @param  string $name
     * @throws ReflectionException
     */
    public function __construct(string $name)
    {
        // Check type as well.
        if (!trait_exists($name)) {
            throw new \ReflectionException(sprintf(
                'Trait "%s" does not exist', $name
            ));
        }

        parent::__construct($name);
    }
}
