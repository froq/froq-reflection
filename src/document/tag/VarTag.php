<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Var tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\VarTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class VarTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'var';

    /**
     * Constructor.
     *
     * @param string|null $type
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(string $type = null, string $name = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('type', 'name'));
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
        if ($name = $this->getName()) {
            $ret .= ' $' . $name;
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

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->getAttribute('name');
    }
}
