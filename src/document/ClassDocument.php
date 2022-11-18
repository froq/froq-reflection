<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document;

use froq\reflection\document\tag\{PackageTag, ClassTag, SinceTag, AuthorTag};

/**
 * Class document.
 *
 * @package froq\reflection\document
 * @class   froq\reflection\document\ClassDocument
 * @author  Kerem Güneş
 * @since   7.0
 */
class ClassDocument extends Document
{
    /**
     * Get package.
     *
     * @return PackageTag|null
     */
    public function getPackage(): PackageTag|null
    {
        return $this->getTag('package', 0);
    }

    /**
     * Get class.
     *
     * @return ClassTag|null
     */
    public function getClass(): ClassTag|null
    {
        return $this->getTag('class', 0);
    }

    /**
     * Get since.
     *
     * @param  int $index
     * @return SinceTag|null
     */
    public function getSince(int $index): SinceTag|null
    {
        return $this->getTag('since', $index);
    }

    /**
     * Get author.
     *
     * @param  int $index
     * @return AuthorTag|null
     */
    public function getAuthor(int $index): AuthorTag|null
    {
        return $this->getTag('author', $index);
    }

    /**
     * Get authors.
     *
     * @return AuthorTag[]|null
     */
    public function getAuthors(): array|null
    {
        return $this->getTag('author');
    }
}
