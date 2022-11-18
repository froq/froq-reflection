<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Copyright tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\CopyrightTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class CopyrightTag extends GenericTag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'copyright';

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
