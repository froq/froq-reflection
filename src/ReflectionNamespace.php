<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection;

/**
 * A namespace reflection class.
 *
 * @package froq\reflection
 * @object  froq\reflection\ReflectionNamespace
 * @author  Kerem Güneş
 * @since   6.0
 */
class ReflectionNamespace implements \Reflector
{
    /** @var string */
    public readonly string $name;

    /**
     * Constructor.
     *
     * @param  string $name
     * @throws ReflectionException
     */
    public function __construct(string $name)
    {
        if ($name !== '' && !preg_test('~^[a-zA-Z_][\w\\\]+$~', $name)) {
            throw new \ReflectionException(sprintf('Invalid namespace: "%s"', $name));
        }

        $this->name = trim($name, '\\');
    }

    /**
     * @magic
     */
    public function __debugInfo(): array
    {
        return ['name' => $this->name];
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        return sprintf('Namespace [ %s ]', $this->name);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get basename.
     *
     * @return string
     */
    public function getBasename(): string
    {
        return substr($this->name, 0, strpos($this->name, '\\') ?: strlen($this->name));
    }

    /**
     * Check class by name.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionClass|null
     */
    public function hasClass(string $name): bool
    {
        return class_exists($this->normalizeName($name));
    }

    /**
     * Get class by name.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionClass|null
     */
    public function getClass(string $name): ReflectionClass|null
    {
        return class_exists($this->normalizeName($name)) ? new ReflectionClass($name) : null;
    }

    /**
     * Get classes.
     *
     * @return array<froq\reflection\ReflectionClass>
     */
    public function getClasses(): array
    {
        return $this->mapNames($this->getClassNames(), ReflectionClass::class);
    }

    /**
     * Get class names.
     *
     * @return array<string>
     */
    public function getClassNames(): array
    {
        return $this->filterNames(get_declared_classes());
    }

    /**
     * Check interface by name.
     *
     * @param  string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return interface_exists($this->normalizeName($name));
    }

    /**
     * Get interface by name.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionInterface|null
     */
    public function getInterface(string $name): ReflectionInterface|null
    {
        return interface_exists($this->normalizeName($name)) ? new ReflectionInterface($name) : null;
    }

    /**
     * Get interfaces.
     *
     * @return array<froq\reflection\ReflectionInterface>
     */
    public function getInterfaces(): array
    {
        return $this->mapNames($this->getInterfaceNames(), ReflectionInterface::class);
    }

    /**
     * Get interface names.
     *
     * @return array<string>
     */
    public function getInterfaceNames(): array
    {
        return $this->filterNames(get_declared_interfaces());
    }

    /**
     * Check trait by name.
     *
     * @param  string $name
     * @return bool
     */
    public function hasTrait(string $name): bool
    {
        return trait_exists($this->normalizeName($name));
    }

    /**
     * Get trait by name.
     *
     * @param  string $name
     * @return froq\reflection\ReflectionTrait|null
     */
    public function getTrait(string $name): ReflectionTrait|null
    {
        return trait_exists($this->normalizeName($name)) ? new ReflectionTrait($name) : null;
    }

    /**
     * Get traits.
     *
     * @return array<froq\reflection\ReflectionTrait>
     */
    public function getTraits(): array
    {
        return $this->mapNames($this->getTraitNames(), ReflectionTrait::class);
    }

    /**
     * Get trait names.
     *
     * @return array<string>
     */
    public function getTraitNames(): array
    {
        return $this->filterNames(get_declared_traits());
    }

    /**
     * Normalize class, interface, trait name.
     */
    private function normalizeName(string &$name): string
    {
        return $name = $this->name . '\\' . ltrim($name, '\\');
    }

    /**
     * Filter class, interface, trait names these start with self namespace.
     *
     * Note: This method returns all names for global scope.
     */
    private function filterNames(array $names): array
    {
        $namespace = ltrim($this->name, '\\') . '\\';

        return array_filter_list($names, fn($name) => str_starts_with($name, $namespace));
    }

    /**
     * Map class, interface, trait names to given related reflection class.
     */
    private function mapNames(array $names, string $class): array
    {
        return array_map(fn($name) => new $class($name), $names);
    }
}
