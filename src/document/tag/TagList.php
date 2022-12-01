<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

use froq\common\interface\Arrayable;

/**
 * Tag list.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\TagList
 * @author  Kerem Güneş
 * @since   7.0
 */
class TagList implements Arrayable, \Countable, \IteratorAggregate, \ArrayAccess
{
    /** List of tags. */
    private array $tags;

    /**
     * Constructor.
     *
     * @param array $tags
     */
    public function __construct(array $tags = [])
    {
        $this->add(...$tags);
    }

    /**
     * Add tags.
     *
     * @param Tag ...$tags
     */
    public function add(Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            $this[] = $tag;
        }
    }

    /**
     * @inheritDoc froq\common\interface\Arrayable
     */
    public function toArray(): array
    {
        return $this->tags;
    }

    /**
     * @inheritDoc Countable
     */
    public function count(): int
    {
        return count($this->tags);
    }

    /**
     * @inheritDoc IteratorAggregate
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->tags);
    }

    /**
     * @inheritDoc ArrayAccess
     */
    public function offsetExists(mixed $index): bool
    {
        return isset($this->tags[$index]);
    }

    /**
     * @inheritDoc ArrayAccess
     */
    public function offsetGet(mixed $index): Tag|null
    {
        return $this->tags[$index] ?? null;
    }

    /**
     * @inheritDoc ArrayAccess
     * @throws     Exception
     */
    public function offsetSet(mixed $index, mixed $tag): void
    {
        ($tag instanceof Tag) || throw new \Exception(format(
            'Argument $tag must be instance of %s, %s given',
            Tag::class, get_type($tag)
        ));

        if ($index === null) {
            $this->tags[] = $tag;
        } else {
            $this->tags[$index] = $tag;
        }
    }

    /**
     * @inheritDoc ArrayAccess
     */
    public function offsetUnset(mixed $index): never
    {
        unset($this->tags[$index]);
    }
}
