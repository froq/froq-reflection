<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Package tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\PackageTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class PackageTag extends Tag
{
    /** Tag ID. */
    public const ID = 'package';

    /**
     * Constructor.
     *
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct(self::ID, null, compact('name'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($name = $this->getName()) {
            $ret .= ' ' . $name;
        }

        return $ret;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->getAttribute('name');
    }

    /**
     * Check name validity.
     *
     * @return bool
     */
    public function hasValidName(): bool
    {
        return preg_test('~^([a-z_\\\][a-z0-9_\\\]+)$~i', (string) $this->getName());
    }
}
