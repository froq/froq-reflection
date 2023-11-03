<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\internal\trait;

use froq\reflection\document\parser\DocumentParser;
use froq\reflection\document\{Document, CallableDocument, InterfaceDocument, TraitDocument,
    ClassDocument, ClassConstantDocument, PropertyDocument, MethodDocument, FunctionDocument, ObjectDocument};
use froq\reflection\{ReflectionCallable, ReflectionInterface, ReflectionTrait};

/**
 * An internal trait, used by reflection classes to get document data.
 *
 * @package froq\reflection\internal\trait
 * @class   froq\reflection\internal\trait\DocumentTrait
 * @author  Kerem Güneş
 * @since   7.0
 * @internal
 */
trait DocumentTrait
{
    /**
     * Get document.
     *
     * @return froq\reflection\document\Document|null
     * @throws Exception
     */
    public function getDocument(): Document|null
    {
        $class = match (true) {
            $this instanceof ReflectionCallable       => CallableDocument::class,
            $this instanceof ReflectionInterface      => InterfaceDocument::class,
            $this instanceof ReflectionTrait          => TraitDocument::class,
            $this instanceof \ReflectionClass         => ClassDocument::class,
            $this instanceof \ReflectionClassConstant => ClassConstantDocument::class,
            $this instanceof \ReflectionProperty      => PropertyDocument::class,
            $this instanceof \ReflectionMethod        => MethodDocument::class,
            $this instanceof \ReflectionFunction      => FunctionDocument::class,
            $this instanceof \ReflectionObject        => ObjectDocument::class,
            default
                => throw new \Exception('Unimplemented reflection: ' . $this::class)
        };

        $source = (string) $this->getDocComment();
        if ($source === '') {
            return null;
        }

        $data = (new DocumentParser($source))->parse();
        return new $class($data['description'], $data['tags']);
    }

    /**
     * Get document description.
     *
     * @return string|null
     * @causes Error
     */
    public function getDocumentDescription(): string|null
    {
        $source = (string) $this->getDocComment();
        if ($source === '') {
            return null;
        }

        $data = (new DocumentParser($source))->parse(false);
        return $data['description'];
    }
}
