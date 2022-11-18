<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Property tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\PropertyTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class PropertyTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'property';

    /**
     * Constructor.
     *
     * @param string|null $type
     * @param string|null $name
     * @param string|null $variant
     * @param string|null $description
     */
    public function __construct(string $type = null, string $name = null, string $variant = null, string $description = null)
    {
        parent::__construct(self::ID, $description, compact('type', 'name', 'variant'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($variant = $this->getVariant()) {
            $ret .= '-' . $variant;
        }
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
     * Get variant.
     *
     * @return string|null
     */
    public function getVariant(): string|null
    {
        return $this->getAttribute('variant');
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
