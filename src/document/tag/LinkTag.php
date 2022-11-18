<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Link tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\LinkTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class LinkTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'link';

    /**
     * Constructor.
     *
     * @param string|null $url
     * @param string|null $description
     */
    public function __construct(string $url = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('url'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($url = $this->getUrl()) {
            $ret .= ' ' . $url;
        }
        if ($description = $this->getDescription()) {
            $ret .= ' ' . $description;
        }

        return $ret;
    }

    /**
     * Get URL.
     *
     * @return string|null
     */
    public function getUrl(): string|null
    {
        return $this->getAttribute('url');
    }
}
