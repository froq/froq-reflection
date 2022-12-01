<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Since tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\SinceTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class SinceTag extends Tag
{
    /** Tag ID. */
    public const ID = 'since';

    /**
     * Constructor.
     *
     * @param string|null $version
     * @param string|null $description
     */
    public function __construct(string $version = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('version'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($version = $this->getVersion()) {
            $ret .= ' ' . $version;
        }
        if ($description = $this->getDescription()) {
            $ret .= ' ' . $description;
        }

        return $ret;
    }

    /**
     * Get version.
     *
     * @return string|null
     */
    public function getVersion(): string|null
    {
        return $this->getAttribute('version');
    }

    /**
     * Get version array.
     *
     * @return array
     */
    public function getVersionArray(): array
    {
        return split(' *, *', (string) $this->getVersion());
    }
}
