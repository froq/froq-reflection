<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Param tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\ParamTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class ParamTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'param';

    /**
     * Constructor.
     *
     * @param string|null $type
     * @param string|null $name
     * @param string|null $description
     * @param bool|null   $variadic
     * @param bool|null   $reference
     * @param string|null $default
     */
    public function __construct(string $type = null, string $name = null, string $description = null,
        bool $variadic = null, bool $reference = null, string $default = null)
    {
        parent::__construct(self::ID, $description, compact('name', 'type', 'variadic', 'reference', 'default'));
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
            $ret .= ' ';

            if ($this->isReference()) {
                $ret .= '&';
            }
            if ($this->isVariadic()) {
                $ret .= '...';
            }

            $ret .= '$' . $name;
        }
        if ($default = $this->getDefault()) {
            $ret .= ' = ' . $default;
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

    /**
     * Check is variadic.
     *
     * @return bool
     */
    public function isVariadic(): bool
    {
        return (bool) $this->getAttribute('variadic');
    }

    /**
     * Check is reference.
     *
     * @return bool
     */
    public function isReference(): bool
    {
        return (bool) $this->getAttribute('reference');
    }

    /**
     * Get default.
     *
     * @return string|null
     */
    public function getDefault(): string|null
    {
        return $this->getAttribute('default');
    }
}
