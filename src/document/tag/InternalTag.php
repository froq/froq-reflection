<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Internal tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\InternalTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class InternalTag extends GenericTag
{
    /** Tag ID. */
    public const ID = 'internal';

    /**
     * Constructor.
     *
     * @param string|null $description
     */
    public function __construct(string $description = null)
    {
        parent::__construct(self::ID, $description);
    }
}
