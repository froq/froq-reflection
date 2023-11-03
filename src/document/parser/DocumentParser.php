<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\parser;

use froq\reflection\document\tag\{TagFactory, TagList};
use RegExp, XArray, XString;

/**
 * Document parser.
 *
 * @package froq\reflection\document\parser
 * @class   froq\reflection\document\parser\DocumentParser
 * @author  Kerem Güneş
 * @since   7.0
 */
class DocumentParser
{
    /** Doc comment source. */
    private string $source;

    /**
     * Constructor.
     *
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = trim($source);
    }

    /**
     * Parse.
     *
     * @param  bool $withTags
     * @return array
     */
    public function parse(bool $withTags = true): array
    {
        $ret = ['description' => '', 'tags' => []];

        /** @var XString */
        $source = xstring($this->source);
        if (!$source || !$source->test('~^/\*\*\s(?:.+)\*/~s')) {
            return $ret;
        }

        static $reTrimStart   = new RegExp('^\s*\*\s{0,1}'),
               $reTrimWrap    = new RegExp('^/\*\*\s*|\s*\*/$'),
               $reReduceSpace = new RegExp('\s+'),
               $reTag         = new RegExp('^\s*@(\w[\w\-]+)');

        /** @var XArray<XString> */
        $lines = $source->xsplit('~(?:\r|\r?\n)~');
        $lines->map(function (string $line) use ($reTrimStart): XString {
            $line = xstring($line);
            $line->remove($reTrimStart);
            return $line;
        });

        if ($lines->count() > 1) {
            // Wrap (/** and */).
            $lines->slice(1, -1);
        } else {
            // Single line (/** ... */).
            $lines->first()->remove($reTrimWrap);
        }

        $description = xstring();

        foreach ($lines as $i => $line) {
            // Stop if @param etc. found.
            if ($line->test($reTag)) {
                break;
            }

            $description->append($line . "\n");
        }

        $ret['description'] = $this->prepareDescription($description);

        if ($withTags) {
            $tags = xarray();
            $from = $i ?? 0;

            foreach ($lines->slice($from) as $line) {
                $line->replace($reReduceSpace, ' ');

                // Start tag line.
                if ($match = $line->match($reTag)) {
                    $tag          = $match[1];
                    $start        = strlen($match[0]);
                    $tags[$tag] ??= xarray();
                    $tags[$tag][] = [$line->sub($start)->trim()];
                } else {
                    // Continue to tag line.
                    $tag = $tags->lastKey();
                    if (isset($tag, $tags[$tag])) {
                        $i = $tags[$tag]->lastKey();
                        if (isset($i, $tags[$tag][$i])) {
                            $line = $line->trim();
                            if (!$line->isEmpty()) {
                                $tags[$tag][$i][] = $line;
                            }
                        }
                    }
                }
            }

            $ret['tags'] = $this->prepareTags($tags);
        }

        return $ret;
    }

    /**
     * Prepare description.
     */
    private function prepareDescription(XString $description): string
    {
        $description->trim();

        return $description->toString();
    }

    /**
     * Prepare tags.
     */
    private function prepareTags(XArray $tags): array
    {
        if ($tags->count()) {
            $factory = new TagFactory();
            $listies = [];

            foreach ($tags as $tag => $items) {
                foreach ($items as $i => $item) {
                    // Overwrite converting to tag instances.
                    $item = $factory->withId($tag)->withBody(join(' ', $item))
                        ->create();

                    $items[$i] = $item;

                    // Collect for normalization.
                    if ($item instanceof TagList) {
                        $listies[$tag][] = $i;
                    }
                }
            }

            // Normalize tag lists merging.
            foreach ($listies as $tag => $is) {
                $tags[$tag] = reduce(
                    $is,      // Indexes.
                    xarray(), // Accumulator.
                    fn(XArray $a, int $i): XArray => (
                        $a->add(...$tags[$tag][$i])
                    )
                );
            }

            // Uniform property tags merging as one "property" field.
            if ($tags->containsKey('property-read', 'property-write')) {
                if ($tags->has('property')) {
                    $tags->get('property')->append(...[
                        ...$tags->get('property-read', []),
                        ...$tags->get('property-write', [])
                    ]);
                } else {
                    $tags->set('property', [
                        ...$tags->get('property-read', []),
                        ...$tags->get('property-write', [])
                    ]);
                }

                // Delete merged tags.
                $tags->deleteKey('property-read', 'property-write');
            }

            $tags->map(fn($tag) => $tag->toArray());
        }

        return $tags->toArray();
    }
}
