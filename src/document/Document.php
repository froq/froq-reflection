<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document;

use froq\reflection\document\builder\DocumentBuilder;
use froq\reflection\document\tag\Tag;

/**
 * Base document class.
 *
 * @package froq\reflection\document
 * @class   froq\reflection\document\Document
 * @author  Kerem Güneş
 * @since   7.0
 */
abstract class Document
{
    /** Parsed description. */
    private string $description;

    /** Parsed tags. */
    private array $tags;

    /**
     * Constructor.
     *
     * @param string $description
     * @param array  $tags
     */
    public function __construct(string $description, array $tags)
    {
        $this->description = $description;
        $this->tags        = $tags;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Check a tag.
     *
     * @param  string $id
     * @return bool
     */
    public function hasTag(string $id): bool
    {
        return isset($this->tags[$id]);
    }

    /**
     * Get a tag.
     *
     * @param  string   $id
     * @param  int|null $index
     * @return Tag|Tag[]|null
     */
    public function getTag(string $id, int $index = null): Tag|array|null
    {
        return isset($index) ? $this->tags[$id][$index] ?? null : $this->tags[$id] ?? null;
    }

    /**
     * Check whether this document is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->description) && empty($this->tags);
    }

    /**
     * Get this document as string.
     *
     * @return string
     */
    public function toString(): string
    {
        $builder = new DocumentBuilder($this->description, $this->tags);
        return $builder->build();
    }

    /**
     * Get this document as comment string.
     *
     * @return string
     */
    public function toCommentString(): string
    {
        $builder = new DocumentBuilder($this->description, $this->tags);
        return $builder->buildComment();
    }
}
