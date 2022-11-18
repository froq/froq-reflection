<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * See tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\SeeTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class SeeTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'see';

    /**
     * Constructor.
     *
     * @param string|null $fqsen
     * @param string|null $description
     */
    public function __construct(string $fqsen = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('fqsen'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($fqsen = $this->getFqsen()) {
            $ret .= ' ' . $fqsen;
        }
        if ($description = $this->getDescription()) {
            $ret .= ' ' . $description;
        }

        return $ret;
    }

    /**
     * Get FQSEN.
     *
     * @return string|null
     */
    public function getFqsen(): string|null
    {
        return $this->getAttribute('fqsen');
    }
}
