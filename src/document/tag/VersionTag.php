<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Version tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\VersionTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class VersionTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'version';

    /**
     * Constructor.
     *
     * @param string|null $version
     * @param string|null $type
     * @param string|null $description
     */
    public function __construct(string $version = null, string $type = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('version', 'type'));
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
     * Get type.
     *
     * @return string|null
     */
    public function getType(): string|null
    {
        return $this->getAttribute('type');
    }
}
