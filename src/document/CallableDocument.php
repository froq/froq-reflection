<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document;

use froq\reflection\document\tag\{ParamTag, ReturnTag, ThrowsTag, CausesTag, TagList};

/**
 * Callable document.
 *
 * @package froq\reflection\document
 * @class   froq\reflection\document\CallableDocument
 * @author  Kerem Güneş
 * @since   7.0
 */
class CallableDocument extends Document
{
    /**
     * Get parameter.
     *
     * @param  int $index
     * @return ParamTag|null
     */
    public function getParameter(int $index): ParamTag|null
    {
        return $this->getTag('param')[$index] ?? null;
    }

    /**
     * Get parameters.
     *
     * @return ParamTag[|null
     */
    public function getParameters(): array|null
    {
        return $this->getTag('param');
    }

    /**
     * Get return.
     *
     * @param  bool $all
     * @return ReturnTag|ReturnTag[]|null
     */
    public function getReturn(bool $all = false): ReturnTag|array|null
    {
        return $all ? $this->getTag('return') : $this->getTag('return', 0);
    }

    /**
     * Get throws.
     *
     * @param  bool $all
     * @return ThrowsTag|ThrowsTag[]|null
     */
    public function getThrows(bool $all = false): ThrowsTag|array|null
    {
        return $all ? $this->getTag('throws') : $this->getTag('throws', 0);
    }

    /**
     * Get causes.
     *
     * @param  bool $all
     * @return CausesTag|CausesTag[]|null
     */
    public function getCauses(bool $all = false): CausesTag|array|null
    {
        return $all ? $this->getTag('causes') : $this->getTag('causes', 0);
    }
}
