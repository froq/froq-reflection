<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document;

use froq\reflection\document\tag\ConstTag;

/**
 * Class constant document.
 *
 * @package froq\reflection\document
 * @class   froq\reflection\document\ClassConstantDocument
 * @author  Kerem Güneş
 * @since   7.0
 */
class ClassConstantDocument extends Document
{
    /**
     * Get constant.
     *
     * @return ConstTag|null
     */
    public function getConstant(): ConstTag|null
    {
        return $this->getTag('const', 0);
    }
}
