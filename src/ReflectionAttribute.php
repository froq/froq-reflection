<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

use froq\reflection\internal\trait\ReferenceTrait;
use froq\reflection\internal\reference\AttributeReference;
use froq\util\Objects;

/**
 * An extended `ReflectionAttribute` class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionAttribute
 * @author  Kerem Güneş
 * @since   7.0
 */
class ReflectionAttribute implements \Reflector
{
    use ReferenceTrait;

    /**
     * As a copy constant.
     *
     * @const int
     */
    public const IS_INSTANCEOF = \ReflectionAttribute::IS_INSTANCEOF;

    /**
     * Constructor.
     *
     * @param \ReflectionAttribute $attribute
     */
    public function __construct(\ReflectionAttribute $attribute)
    {
        $this->reference = new AttributeReference(
            attribute : $attribute,
            arguments : $attribute->getArguments()
        );
    }

    /**
     * Proxy for reference properties.
     *
     * @param  string $property
     * @return mixed
     * @throws ReflectionException
     * @magic
     */
    public function __get(string $property): mixed
    {
        switch ($property) {
            case 'name':
                return $this->getName();
            case 'arguments':
                return $this->getArguments();
            default:
                throw new \ReflectionException(sprintf(
                    'Undefined property %s::$%s', $this::class, $property
                ));
        }
    }

    /**
     * @proxy
     * @magic
     */
    public function __toString(): string
    {
        return $this->reference->attribute->__toString();
    }

    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        return ['name' => $this->getName(), 'arguments' => $this->getArguments()];
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->reference->attribute->getName();
    }

    /**
     * Get short name.
     *
     * @return string
     */
    public function getShortName(): string
    {
        return Objects::getShortName($this->getName());
    }

    /**
     * Get namespace.
     *
     * @param  bool $baseOnly
     * @return string
     */
    public function getNamespace(bool $baseOnly = false): string
    {
        return Objects::getNamespace($this->getName(), $baseOnly);
    }

    /**
     * Get declaring namespace.
     *
     * @return froq\reflection\ReflectionNamespace
     */
    public function getDeclaringNamespace(): ReflectionNamespace
    {
        return new ReflectionNamespace($this->getNamespace());
    }

    /**
     * Get a single argument by given name / position.
     *
     * @param  string|int $offset
     * @param  mixed|null $default
     * @return mixed
     */
    public function getArgument(string|int $offset, mixed $default = null): mixed
    {
        return $this->reference->arguments[$offset] ?? $default;
    }

    /**
     * @proxy
     */
    public function getArguments(): array
    {
        return $this->reference->arguments;
    }

    /**
     * @proxy
     */
    public function getTarget(): int
    {
        return $this->reference->attribute->getTarget();
    }

    /**
     * @proxy
     */
    public function isRepeated(): bool
    {
        return $this->reference->attribute->isRepeated();
    }

    /**
     * @proxy
     */
    public function newInstance(): object
    {
        return $this->reference->attribute->newInstance();
    }
}
