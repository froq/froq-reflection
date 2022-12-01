<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Class tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\ClassTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class ClassTag extends Tag
{
    /** Tag ID. */
    public const ID = 'class';

    /**
     * Constructor.
     *
     * @param string|null $name
     * @param string|null $namespace
     */
    public function __construct(string $name = null, string $namespace = null)
    {
        if ($name !== null && $namespace === null) {
            $namespace = get_class_namespace($name) ?: null;
        }

        parent::__construct(self::ID, null, compact('name', 'namespace'));
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
     * Get package name.
     *
     * @return string|null
     */
    public function getPackageName(): string|null
    {
        return $this->getAttribute('namespace');
    }

    /**
     * Check name validity.
     *
     * @return bool
     */
    public function hasValidName(): bool
    {
        return preg_test('~^([a-z_\\\][a-z0-9_\\\]+)$~i', (string) $this->getName());
    }

    /**
     * Check package name validity.
     *
     * @return bool
     */
    public function hasValidPackageName(): bool
    {
        return preg_test('~^([a-z_\\\][a-z0-9_\\\]+)$~i', (string) $this->getPackageName());
    }
}
