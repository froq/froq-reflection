<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * Kind of missing reflection class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionInterface
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionInterface extends ReflectionClass
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
        if (!interface_exists($name)) {
            throw new \ReflectionException(sprintf(
                'Interface "%s" does not exist', $name
            ));
        }

        parent::__construct($name);
    }
}
