<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\trait;

/**
 * An internal trait, used by reflection classes to keep reference information
 * in place (eg: `name`, `names`, `nullable` for `ReflectionType`).
 *
 * @package froq\reflection\internal\trait
 * @class   froq\reflection\internal\trait\ReferenceTrait
 * @author  Kerem Güneş
 * @since   6.2
 * @internal
 */
trait ReferenceTrait
{
    /**
     * Reference holder.
     *
     * @var stdClass
     */
    private readonly \stdClass $reference;

    /**
     * Set reference.
     *
     * @param  array|stdClass $reference
     * @return void
     */
    public final function setReference(array|\stdClass $reference): void
    {
        $this->reference = (object) $reference;
    }

    /**
     * Get reference (as clone so that to keep away from mutations).
     *
     * @return stdClass
     */
    public final function getReference(): \stdClass
    {
        return clone $this->reference;
    }
}
