<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An extended `ReflectionFunction` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionFunction
 * @author  Kerem Güneş
 * @since   5.27, 6.0
 */
class ReflectionFunction extends \ReflectionFunction
{
    use trait\CallableTrait;
}
