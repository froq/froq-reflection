<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Generic tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\GenericTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class GenericTag extends Tag
{
    /**
     * Constructor.
     *
     * @param string      $id
     * @param string|null $description
     * @param mixed    ...$attributes
     */
    public function __construct(string $id, string $description = null, mixed ...$attributes)
    {
        if ($id === 'inheritdoc') {
            $id = 'inheritDoc';
        }

        parent::__construct($id, $description, $attributes);
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret = '@' . $this->getId();

        if ($description = $this->getDescription()) {
            $ret .= ' ' . $this->getDescription();
        }

        return $ret;
    }
}
