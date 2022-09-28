<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
declare(strict_types=1);

namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionParameter;
use Set;

/**
 * Parameter reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @object  froq\reflection\internal\reflector\ParameterReflector
 * @author  Kerem Güneş
 * @since   6.0
 * @internal
 */
class ParameterReflector extends Reflector
{
    /**
     * Set of parameters.
     *
     * @return Set
     */
    public function parameters(): Set
    {
        return (new Set($this->collect()))
            ->map(fn($name) => $this->convert($name));
    }

    /**
     * Check parameter existence.
     *
     * @return bool
     */
    public function hasParameter(string|int $offset): bool
    {
        foreach ($this->collect() as $position => $name) {
            if ($offset == $name || $offset === $position) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get parameter.
     *
     * @return froq\reflection\ReflectionParameter|null
     */
    public function getParameter(string|int $offset): ReflectionParameter|null
    {
        foreach ($this->collect() as $position => $name) {
            if ($offset == $name || $offset === $position) {
                return $this->convert($name);
            }
        }
        return null;
    }

    /**
     * Get parameters.
     *
     * @return array<froq\reflection\ReflectionParameter>
     */
    public function getParameters(array $offsets = null): array
    {
        $names = $this->collect();

        if ($offsets) {
            foreach ($names as $position => $name) {
                if (!in_array($name, $offsets, true) &&
                    !in_array($position, $offsets, true)) {
                    unset($names[$position]);
                }
            }
        }

        return array_map([$this, 'convert'], array_values($names));
    }

    /**
     * Get parameter names.
     *
     * @return array<string>
     */
    public function getParameterNames(): array
    {
        return $this->collect();
    }

    /**
     * Get parameter (default) values.
     *
     * @param  bool $assoc
     * @return array<mixed>
     */
    public function getParameterValues(bool $assoc = false): array
    {
        $values = array_map(
            fn($name) => $this->convert($name)->getDefaultValue(),
            $names = $this->collect()
        );

        return $assoc ? array_combine($names, $values) : $values;
    }

    /**
     * Collect parameter names.
     */
    private function collect(): array
    {
        $ret = [];

        foreach ($this->ref->reference->reflection->getParameters() as $parameter) {
            $ret[] = $parameter->name;
        }

        return $ret;
    }

    /**
     * Convert parameters to instances.
     */
    private function convert(string $name): ReflectionParameter
    {
        return new ReflectionParameter($this->ref->reference->callable, $name);
    }
}
