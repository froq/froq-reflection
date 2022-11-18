<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag;

use froq\reflection\document\tag\parser\TagParser;

/**
 * Tag factory.
 *
 * @package froq\reflection\document\tag
 * @class   froq\reflection\document\tag\TagFactory
 * @author  Kerem Güneş
 * @since   7.0
 */
class TagFactory
{
    /**
     * Tag ID.
     *
     * @var string
     */
    private string $id;

    /**
     * Tag body.
     *
     * @var string
     */
    private string $body;

    /**
     * Parser.
     *
     * @var TagParser
     */
    private TagParser $parser;

    /**
     * Map of tag classes.
     *
     * @var array
     */
    private static array $map;

    /**
     * Constructor.
     *
     * @param string|null $id
     * @param string|null $body
     */
    public function __construct(string $id = null, string $body = null)
    {
        isset($id)   && $this->withId($id);
        isset($body) && $this->withBody($body);
        $this->parser = new TagParser();

        self::$map ??= $this->generateMap();
    }

    /**
     * Assign id.
     *
     * @param  string $id
     * @return self
     */
    public function withId(string $id): self
    {
        $this->id = lower($id);
        return $this;
    }

    /**
     * Assign body.
     *
     * @param  string $id
     * @return self
     */
    public function withBody(string $body): self
    {
        $this->body = trim($body);
        return $this;
    }

    /**
     * Create.
     *
     * @return Tag|TagList
     */
    public function create(): Tag|TagList
    {
        $id    = $this->id   ?? throw new \Exception('No id given yet, call withId()');
        $body  = $this->body ?? throw new \Exception('No body given yet, call withBody()');

        $class = $this->getClassFor($id);
        $tag   = $this->prepareTag($id, $body);

        $data  = $this->parser->parse($tag);

        if ($class === GenericTag::class) {
            $data += ['id' => $id];
        }

        if (is_list($data)) {
            $tags = new TagList();
            foreach ($data as $dat) {
                $tag    = new $class(...$dat);
                $tags[] = $tag;
            }
            return $tags;
        } else {
            $tag = new $class(...$data);
            return $tag;
        }
    }

    /**
     * Prepare tag for parsing.
     */
    private function prepareTag(string $id, string $body): string
    {
        if (str_starts_with($id, '@')) {
            $id = substr($id, 1);
        }
        if (str_starts_with($body, $id)) {
            $body = substr($body, strlen($id));
        }

        return '@' . $id . ' ' . $body;
    }

    /**
     * Get class for (by) given id.
     */
    private function getClassFor(string $id): string
    {
        if (isset(self::$map[$id])) {
            return self::$map[$id];
        }

        switch ($id) {
            case 'property-read':
            case 'property-write':
                return PropertyTag::class;
            default:
                return GenericTag::class;
        }
    }

    /**
     * Generate a tag map for once for tag class look ups.
     */
    private function generateMap(): array
    {
        $ret = [];
        $nop = ['Tag' => 1, 'TagFactory' => 1, 'TagList' => 1, 'GenericTag' => 1];

        foreach (glob(__DIR__ . '/../tag/*Tag.php') as $file) {
            $base = filename($file);
            if (isset($nop[$base])) {
                continue;
            }

            $id       = lower(slice($base, 0, -3));
            $class    = __NAMESPACE__ . '\\' . $base;
            $ret[$id] = $class;
        }

        return $ret;
    }
}
