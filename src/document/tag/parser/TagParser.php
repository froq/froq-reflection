<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag\parser;

/**
 * Tag parser.
 *
 * @package froq\reflection\document\tag\parser
 * @class   froq\reflection\document\tag\parser\TagParser
 * @author  Kerem Güneş
 * @since   7.0
 */
class TagParser
{
    /**
     * Map of tags.
     */
    private static array $map;

    /**
     * Constructor.
     */
    public function __construct()
    {
        self::$map ??= $this->generateMap();
    }

    /**
     * Parse.
     *
     * @param  string $tag
     * @return array
     */
    public function parse(string $tag): array
    {
        $tag = trim($tag);

        // Be like: @param int $id ...
        if (strpos($tag, '@') !== 0) {
            return [];
        }

        [$tag, $body] = split(' +', $tag, 2);

        $id     = slice($tag, 1);
        $body   = join(' ', [$tag, $body]);

        $method = $this->getMethodFor($id);

        return $this->$method($body);
    }

    /**
     * Parse author.
     */
    private function parseAuthor(string $body): array
    {
        $values = $this->extract(TagPattern::AUTHOR, $body, 2);

        $values = array_apply($values, fn(?string $value): ?string => (
            $value !== null ? trim($value) : $value
        ));

        return $this->combine(['name', 'email'], $values);
    }

    /**
     * Parse causes.
     */
    private function parseCauses(string $body): array
    {
        $values = $this->extract(TagPattern::CAUSES, $body, 2);

        return $this->combine(['type', 'description'], $values);
    }

    /**
     * Parse class.
     */
    private function parseClass(string $body): array
    {
        $values = $this->extract(TagPattern::CLASS_, $body, 2);

        // Add namespace.
        if (isset($values[0])) {
            $values[1] = get_class_namespace($values[0]);
        }

        return $this->combine(['name', 'namespace'], $values);
    }

    /**
     * Parse const.
     */
    private function parseConst(string $body): array
    {
        $values = $this->extract(TagPattern::CONST, $body, 3);

        return $this->combine(['type', 'name', 'description'], $values);
    }

    /**
     * Parse generic.
     */
    private function parseGeneric(string $body): array
    {
        $values = $this->extract(TagPattern::GENERIC, $body, 1);

        return $this->combine(['description'], $values);
    }

    /**
     * Parse deprecated.
     */
    private function parseDeprecated(string $body): array
    {
        $values = $this->extract(TagPattern::DEPRECATED, $body, 2);

        return $this->combine(['version', 'description'], $values);
    }

    /**
     * Parse link.
     */
    private function parseLink(string $body): array
    {
        $values = $this->extract(TagPattern::LINK, $body, 2);

        return $this->combine(['url', 'description'], $values);
    }

    /**
     * Parse method.
     */
    private function parseMethod(string $body): array
    {
        $values = $this->extract(TagPattern::METHOD, $body, 5);

        if (isset($values[0])) {
            $values[0] = trim($values[0]);
        }

        // Normalize returns reference.
        $values[1] = $values[1] === '&';

        // Convert parameters.
        if (isset($values[3])) {
            $parameters = [];
            foreach (split(' *, *', $values[3]) as $body) {
                $parameter = $this->parseParam('@param ' . $body);
                if (isset($parameter['name'])) {
                    $parameters[$parameter['name']] = $parameter;
                }
            }
            $values[3] = $parameters;
        }

        return $this->combine(['returnType', 'returnsReference', 'name', 'parameters', 'description'], $values);
    }

    /**
     * Parse package.
     */
    private function parsePackage(string $body): array
    {
        $values = $this->extract(TagPattern::PACKAGE, $body, 1);

        return $this->combine(['name'], $values);
    }

    /**
     * Parse param.
     */
    private function parseParam(string $body): array
    {
        $values = $this->extract(TagPattern::PARAM, $body, 7);

        // Add variadic/reference fields.
        if (!empty($values[1])) {
            $values[4] = str_contains($values[1], '.') ?: null;
            $values[5] = str_contains($values[1], '&') ?: null;
        }
        unset($values[1]);

        // Grab default from description part (for @method tags).
        if (!empty($values[3]) && $values[3][0] === '=') {
            $values[6] = split('= *', $values[3])[0];
            $values[3] = null;
        }

        return $this->combine(['type', 'name', 'description', 'variadic', 'reference', 'default'], $values);
    }

    /**
     * Parse property.
     */
    private function parseProperty(string $body): array
    {
        $values = $this->extract(TagPattern::PROPERTY, $body, 3);

        // Add variant.
        $values[] = grep('~@?property-(read|write)~', $body);

        return $this->combine(['type', 'name', 'description', 'variant'], $values);
    }

    /**
     * Parse return.
     */
    private function parseReturn(string $body): array
    {
        $values = $this->extract(TagPattern::RETURN, $body, 2);

        return $this->combine(['type', 'description'], $values);
    }

    /**
     * Parse see.
     */
    private function parseSee(string $body): array
    {
        $values = $this->extract(TagPattern::SEE, $body, 2);

        return $this->combine(['fqsen', 'description'], $values);
    }

    /**
     * Parse since.
     */
    private function parseSince(string $body): array
    {
        $values = [];

        if (strpos($body, ',') > -1) {
            $parts = split(' *, *', $body, 2);
            $parts = array_flat([
                $parts[0],
                array_apply(
                    split(' *, *', strval($parts[1])),
                    fn(string $body): string => '@since ' . $body
                )
            ]);

            foreach ($parts as $body) {
                $values[] = $this->combine(
                    ['version', 'description'],
                    $this->extract(TagPattern::SINCE, $body, 2)
                );
            }
        } else {
            $values[] = $this->combine(
                ['version', 'description'],
                $this->extract(TagPattern::SINCE, $body, 2)
            );
        }

        // Let singles stay single.
        return count($values) === 1 ? $values[0] : $values;
    }

    /**
     * Parse throws.
     */
    private function parseThrows(string $body): array
    {
        $values = $this->extract(TagPattern::THROWS, $body, 2);

        return $this->combine(['type', 'description'], $values);
    }

    /**
     * Parse uses.
     */
    private function parseUses(string $body): array
    {
        $values = $this->extract(TagPattern::USES, $body, 2);

        return $this->combine(['fqsen', 'description'], $values);
    }

    /**
     * Parse var.
     */
    private function parseVar(string $body): array
    {
        $values = $this->extract(TagPattern::VAR, $body, 3);

        return $this->combine(['type', 'name', 'description'], $values);
    }

    /**
     * Parse version.
     */
    private function parseVersion(string $body): array
    {
        $values = $this->extract(TagPattern::VERSION, $body, 2);

        // Add type.
        if (!empty($values[0])) {
            $version  = $values[0];
            $values[] = (
                $version[0] === '$' ? 'CVS' : (
                    $version[0] === '@' ? 'PEAR' : 'SEMVER'
                )
            );

            // Clean.
            if (strpfx($version, ['$', '@'])) {
                [$wrapper, $version] = [$version[0], substr($version, 1, -1)];
                $values[0] = $wrapper . trim($version) . $wrapper;
            }
        }

        return $this->combine(['version', 'description', 'type'], $values);
    }

    /**
     * Extract tag data running given regex pattern on body.
     */
    private function extract(string $pattern, string $body, int $pad = 0): array
    {
        preg_match($pattern, $body, $match, PREG_UNMATCHED_AS_NULL);

        return array_pad(array_slice($match, 1), $pad, null);
    }

    /**
     * Combine given tag data with keys.
     */
    private function combine(array $keys, array $values): array
    {
        $values = array_apply($values, fn(mixed $value): mixed => (
            $value !== null && $value !== '' ? $value : null
        ));

        return array_combine($keys, $values);
    }

    /**
     * Get a method for given ID.
     */
    private function getMethodFor(string $id): string
    {
        // A defined method here.
        if (isset(self::$map[$id])) {
            return 'parse' . ucfirst($id);
        }

        switch ($id) {
            case 'property-read':
            case 'property-write':
                return 'parseProperty';
            default:
                return 'parseGeneric';
        }
    }

    /**
     * Generate a tag map for once for parse method look ups.
     */
    private function generateMap(): array
    {
        $ret = [];
        $ref = new \XReflectionClass(TagPattern::class);

        foreach ($ref->getConstantNames() as $name) {
            $id = lower($name);

            // Make up.
            if ($id === 'class_') {
                $id = 'class';
            }

            // Tick.
            $ret[$id] = 1;
        }

        return $ret;
    }
}
