<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Author tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\AuthorTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class AuthorTag extends Tag
{
    /** Tag ID. */
    public const ID = 'author';

    /**
     * Constructor.
     *
     * @param string|null $name
     * @param string|null $email
     */
    public function __construct(string $name = null, string $email = null)
    {
        parent::__construct(self::ID, null, compact('name', 'email'));
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
        if ($email = $this->getEmail()) {
            $ret .= ' <' . $email . '>';
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
     * Get email.
     *
     * @return string|null
     */
    public function getEmail(): string|null
    {
        return $this->getAttribute('email');
    }
}
