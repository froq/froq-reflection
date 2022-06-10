<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

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
    use internal\trait\CallableTrait;
}
