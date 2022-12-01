<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\builder;

use froq\reflection\document\Document;
use froq\reflection\document\tag\TagList;

/**
 * Document builder.
 *
 * @package froq\reflection\document\builder
 * @class   froq\reflection\document\builder\DocumentBuilder
 * @author  Kerem Güneş
 * @since   7.0
 */
class DocumentBuilder
{
    /** Description content. */
    private string $description;

    /** Parsed tags. */
    private array $tags;

    /**
     * Constructor.
     *
     * @param string $description
     * @param array  $tags
     */
    public function __construct(string $description, array $tags)
    {
        $this->description = $description;
        $this->tags        = $tags;
    }

    /**
     * Build.
     *
     * @return string
     */
    public function build(): string
    {
        $lines = $this->prepareLines();

        return join("\n", $lines);
    }

    /**
     * Build comment.
     *
     * @return string
     */
    public function buildComment(): string
    {
        $lines = $this->prepareLines();

        $lines = array_apply($lines, function (string $line): string {
            $line = " * {$line}";
            $line = str_replace("\n", "\n * ", $line);
            return $line;
        });

        $lines = ['/**', ...$lines, ' */'];

        return join("\n", $lines);
    }

    private function prepareLines(): array
    {
        $lines       = [];
        $tagMaxLen   = $typeMaxLen = $nameMaxLen  = 0;
        $reAlignable = '~^(param|property(-(read|write))?)$~';

        /** @var array<string, froq\reflection\document\tag\Tag> $tags */
        foreach ($this->tags as $tags) {
            /** @var froq\reflection\document\tag\Tag $tag */
            foreach ($tags as $tag) {
                $line   = (string) $tag;
                $tagLen = strlen($tag->getId());

                if ($tag->getId() === 'property' && ($variant = $tag->getVariant())) {
                    $tagLen += strlen($variant) + 1;
                }

                $isNotEmptyTag  = strlen($line) - 1 > $tagLen;
                $isAlignableTag = $isNotEmptyTag && preg_test($reAlignable, $tag->getId());

                if ($isNotEmptyTag && $tagMaxLen < $tagLen) {
                    $tagMaxLen = $tagLen;
                }
                if ($isAlignableTag && ($type = $tag->getType())) {
                    $typeMaxLen = max($typeMaxLen, strlen($type));
                    $nameMaxLen = max($nameMaxLen, strlen($tag->getName()));
                    if ($tag->getId() === 'param') {
                        $tag->isVariadic()  && $nameMaxLen += 3; // For "..." stuff.
                        $tag->isReference() && $nameMaxLen += 1; // For "&" stuff.
                    }
                }

                $lines[] = $line;
            }
        }

        $this->alignTags($lines, $tagMaxLen, $typeMaxLen, $nameMaxLen);

        // Normalize since tags.
        if (isset($this->tags['since'])) {
            $this->joinSinceTags($lines, $tagMaxLen);
        }

        // Prepend description line(s).
        if ($this->description !== '') {
            $lines = $lines
                   ? [$this->description, '', ...$lines]
                   : [$this->description];
        }

        return $lines;
    }

    /**
     * Align tags with proper space.
     */
    private function alignTags(array &$lines, int $tagMaxLen, int $typeMaxLen, int $nameMaxLen): void
    {
        foreach ($lines as &$line) {
            if (($spos = strpos($line, ' ')) > 1) {
                $tag   = substr($line, 1, $spos - 1);
                $parts = split(' +', $line, 4);
                $temps = [];
                $start = 1;

                // ID part (eg: @param).
                $temps[] = $parts[0] .= str_repeat(' ', abs($tagMaxLen - strlen($parts[0]) + 1));

                // Special case of @param tags.
                if ($tag === 'param') {
                    if ($parts[1] !== null) {
                        // Type part (eg: int).
                        $temps[] = $parts[1] .= str_repeat(' ', abs($typeMaxLen - strlen($parts[1])));
                        $start++;
                    }
                    if ($parts[2] !== null) {
                        // Name part (eg: $foo).
                        $temps[] = $parts[2] .= str_repeat(' ', abs($nameMaxLen - strlen($parts[2])) - 1);
                        $start++;
                    }
                }

                $line = trim(join(' ', [...$temps, ...slice($parts, $start)]));
            }
        }
    }

    /**
     * Join "since" tags normalizing.
     *
     * Before:
     * `1.0, 2.0 Description text.`
     *
     * After:
     * `1.0`
     * `2.0 Description text.`
     */
    private function joinSinceTags(array &$lines, int $tagMaxLen): void
    {
        $versions = [];

        foreach ($lines as $i => $line) {
            // Tags with no description but with version only.
            if (preg_match('~^@since\s+(\d[\d\.]+)$~', $line, $match)) {
                $versions[$i] = $match[1];
            }
        }

        if ($versions) {
            $index = null;

            // Drop indexes, but keep first.
            foreach ($versions as $i => $_) {
                unset($lines[$i]);
                $index ??= $i;
            }

            $lines[$index] = '@since ' . str_concat(
                str_repeat(' ', $tagMaxLen - 5),
                join(', ', $versions)
            );

            ksort($lines);
        }
    }
}
