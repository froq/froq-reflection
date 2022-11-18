<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Base tag class.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\Tag
 * @author  Kerem Güneş
 * @since   7.0
 */
abstract class Tag implements \Stringable
{
    /**
     * Tag ID.
     *
     * @const string|null
     * @abstract
     */
    public const ID = null;

    /**
     * ID (aka Name).
     *
     * @var string
     */
    private string $id;

    /**
     * Description text.
     *
     * @var string|null
     */
    private string|null $description;

    /**
     * Attributes.
     *
     * @var array|null
     */
    private array|null $attributes;

    /**
     * Constructor.
     *
     * @param string      $id
     * @param string|null $description
     * @param mixed    ...$attributes
     */
    public function __construct(string $id, string $description = null, mixed ...$attributes)
    {
        $this->id          = $id;
        $this->description = $description;
        $this->attributes  = $this->prepareAttributes($attributes);
    }

    /**
     * Get ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }

    /**
     * Get attributes.
     *
     * @return array|null
     */
    public function getAttributes(): array|null
    {
        return $this->attributes;
    }

    /**
     * Get attribute.
     *
     * @param  string     $name
     * @param  mixed|null $default
     * @return mixed|null
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Prepare attributes.
     */
    private function prepareAttributes(array $attributes): array|null
    {
        // Not named params.
        if (is_list($attributes)) {
            $attributes = first($attributes);
        }

        return $attributes;
    }
}
