<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

/**
 * Method tag.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\MethodTag
 * @author  Kerem Güneş
 * @since   7.0
 */
class MethodTag extends Tag
{
    /**
     * Tag ID.
     *
     * @const string
     */
    public const ID = 'method';

    /**
     * Constructor.
     *
     * @param string|null $name
     * @param array|null  $parameters
     * @param string|null $description
     * @param string|null $returnType
     * @param bool        $returnsReference
     */
    public function __construct(string $name = null, array $parameters = null,
        string $description = null, string $returnType = null, bool $returnsReference = false)
    {
        if ($parameters !== null) {
            foreach ($parameters as &$parameter) {
                $parameter = new ParamTag(...$parameter);
            }
        }

        parent::__construct(self::ID, $description, compact('returnType', 'name', 'parameters', 'returnsReference'));
    }

    /**
     * @magic
     */
    public function __toString(): string
    {
        $ret  = '@' . $this->getId();

        if ($returnType = $this->getReturnType()) {
            $ret .= ' ' . $returnType;
        }
        if ($name = $this->getName()) {
            $ret .= ' ';

            if ($this->returnsReference()) {
                $ret .= '&';
            }

            $ret .= $name;
            $ret .= '(';

            if ($parameters = $this->getParameters()) {
                $tmp = [];
                foreach ($parameters as $parameter) {
                    $tmp[] = substr((string) $parameter, strlen('@param') + 1);
                }
                $ret .= join(', ', $tmp);
            }

            $ret .= ')';
        }
        if ($description = $this->getDescription()) {
            $ret .= ' ' . $description;
        }

        return $ret;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->getAttribute('name');
    }

    /**
     * Get parameters.
     *
     * @return ParamTag[]|null
     */
    public function getParameters(): array|null
    {
        return $this->getAttribute('parameters');
    }

    /**
     * Get parameter.
     *
     * @return ParamTag|null
     */
    public function getParameter(string $name): ParamTag|null
    {
        return $this->getAttribute('parameters')[$name] ?? null;
    }

    /**
     * Get return type.
     *
     * @return string|null
     */
    public function getReturnType(): string|null
    {
        return $this->getAttribute('returnType');
    }

    /**
     * Check returns reference.
     *
     * @return bool
     */
    public function returnsReference(): bool
    {
        return (bool) $this->getAttribute('returnsReference');
    }
}
