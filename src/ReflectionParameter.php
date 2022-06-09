<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection;

/**
 * An extended `ReflectionParameter` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionParameter
 * @author  Kerem Güneş
 * @since   5.31, 6.0
 */
class ReflectionParameter extends \ReflectionParameter
{
    /**
     * Check default value existence.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->isOptional() && !$this->isVariadic();
    }

    /**
     * Get default value return null.
     *
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->hasDefaultValue() ? parent::getDefaultValue() : null;
    }

    /** @override */
    public function isDefaultValueConstant(): bool
    {
        return $this->hasDefaultValue() ? parent::isDefaultValueConstant() : false;
    }

    /** @override */
    public function getDefaultValueConstantName(): string|null
    {
        return $this->hasDefaultValue() ? parent::getDefaultValueConstantName() : null;
    }

    /** @override */
    public function getType(): ReflectionType|null
    {
        if ($type = parent::getType()) {
            return ReflectionType::from($type);
        }
        return null;
    }

    /**
     * Get types (if available).
     *
     * @return array<froq\reflection\ReflectionType>|null
     */
    public function getTypes(): array|null
    {
        return $this->getType()?->getTypes();
    }
}
