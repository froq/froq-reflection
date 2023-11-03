<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\trait;

use froq\reflection\internal\reference\Reference;

/**
 * An internal trait, used by reflection classes to keep reference information
 * in place (eg: `name`, `names`, `nullable` for `ReflectionType`).
 *
 * @package froq\reflection\internal\trait
 * @class   froq\reflection\internal\trait\ReferenceTrait
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
trait ReferenceTrait
{
    /**
     * Reference holder instance.
     */
    public readonly Reference $reference;
}
