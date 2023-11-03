<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document;

use froq\reflection\document\tag\VarTag;

/**
 * Property document.
 *
 * @package froq\reflection\document
 * @class   froq\reflection\document\PropertyDocument
 * @author  Kerem Güneş
 * @since   7.0
 */
class PropertyDocument extends Document
{
    public function getVariable(): VarTag|null
    {
        return $this->getTag('var', 0);
    }
}
