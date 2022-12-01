<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Api tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\ApiTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class ApiTag extends GenericTag
{
    /** Tag ID. */
    public const ID = 'api';

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
