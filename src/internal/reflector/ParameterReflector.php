<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\reflector;

use froq\reflection\ReflectionParameter;
use Set;

/**
 * Parameter reflector class.
 *
 * @package froq\reflection\internal\reflector
 * @class   froq\reflection\internal\reflector\ParameterReflector
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
        return new Set($this->getParameters());
    }

    /**
     * Check parameter existence.
     *
     * @param  string|int $offset
     * @return bool
     */
    public function hasParameter(string|int $offset): bool
    {
        if ($this->collect([$offset])) {
            return true;
        }
        return false;
    }

    /**
     * Get parameter.
     *
     * @param  string|int $offset
     * @return froq\reflection\ReflectionParameter|null
     */
    public function getParameter(string|int $offset): ReflectionParameter|null
    {
        if ($names = $this->collect([$offset])) {
            return $this->convert($names[0]);
        }
        return null;
    }

    /**
     * Get parameters.
     *
     * @param  array<string|int>|null $offset
     * @return array<froq\reflection\ReflectionParameter>
     */
    public function getParameters(array $offsets = null): array
    {
        return array_apply(
            $this->collect($offsets),
            fn(string $name): ReflectionParameter => $this->convert($name),
        );
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
        $values = array_apply(
            $names = $this->collect(),
            fn(string $name): mixed => $this->convert($name)->getDefaultValue()
        );

        return $assoc ? array_combine($names, $values) : $values;
    }

    /**
     * Collect parameter names.
     */
    private function collect(array $offsets = null): array
    {
        $ret = [];

        foreach ($this->reflector->reference->reflection->getParameters() as $parameter) {
            $ret[] = $parameter->name;
        }

        if ($offsets) {
            foreach ($ret as $position => $name) {
                if (!in_array($name, $offsets, true) &&
                    !in_array($position, $offsets, true)) {
                    unset($ret[$position]);
                }
            }
            $ret = array_list($ret);
        }

        return $ret;
    }

    /**
     * Convert parameters to instances.
     */
    private function convert(string $name): ReflectionParameter
    {
        return new ReflectionParameter($this->reflector->reference->callable, $name);
    }
}
