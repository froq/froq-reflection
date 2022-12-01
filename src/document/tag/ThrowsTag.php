<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Throws tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\ThrowsTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class ThrowsTag extends Tag
{
    /** Tag ID. */
    public const ID = 'throws';

    /**
     * Constructor.
     *
     * @param string|null $type
     * @param string|null $description
     */
    public function __construct(string $type = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('type'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($type = $this->getType()) {
            $ret .= ' ' . $type;
        }
        if ($description = $this->getDescription()) {
            $ret .= ' ' . $description;
        }

        return $ret;
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
