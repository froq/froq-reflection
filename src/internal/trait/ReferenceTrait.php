<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\trait;

use froq\reflection\internal\reference\Reference;
// use froq\util\Objects;

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
     * @var froq\reflection\internal\reference\Reference
     */
    private readonly Reference $reference;

    /**
     * Set reference.
     *
     * @param  froq\reflection\internal\reference\Reference $reference
     * @return void
     */
    public function setReference(Reference $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * Get reference.
     *
     * @return froq\reflection\internal\reference\Reference
     */
    public function getReference(): Reference
    {
        return $this->reference;

        // // Should really do this (to keep away from mutations)?
        // return Objects::clone($this->reference);
    }
}
