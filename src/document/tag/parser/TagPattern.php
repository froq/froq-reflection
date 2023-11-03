<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-reflection
 */
namespace froq\reflection\document\tag\parser;

/**
 * Tag pattern.
 *
 * @package froq\reflection\document\tag\parser
 * @class   froq\reflection\document\tag\parser\TagPattern
 * @author  Kerem Güneş
 * @since   7.0
 */
class TagPattern
{
    /**
     * Pattern for parsing, matches following format:
     *
     * [at]author ["Name"] ["<Email>"|""]
     */
    public const AUTHOR = '~^(?:@author) +([^\<]+)(?: *\<([^\>]+)\>)?$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]causes ["Type"] ["Description"|""]
     */
    public const CAUSES = '~^(?:@causes) +([^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]class ["Namespace"|""]["Name"]
     */
    public const CLASS_ = '~^(?:@class) +([\w\\\]+)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]const ["Type"] ["Name"|""] ["Description"|""]
     */
    public const CONST = '~^(?:@const) +([^ ]+) *(\w+)? *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]["Tag ID"] ["Description"|""]
     */
    public const GENERIC = '~^(?:@\w[\w\-]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]deprecated ["Version"] ["Description"|""]
     */
    public const DEPRECATED = '~^(?:@deprecated) +(\d[\d\.]+)? *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]link ["URL"] ["Description"|""]
     */
    public const LINK = '~^(?:@link) +((?:\w+:)?//[^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]method ["Return Type"|""] ["&"|""]["Name"](["Parameters"|""]) ["Description"|""]
     */
    public const METHOD = '~^(?:@method) +([^@]+) +(&)?(\w+)\((.*)\) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]package ["Name"]
     */
    public const PACKAGE = '~^(?:@package) +([\w\\\]+)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [@]param ["Type"] ["&"|"&..."|"..."|""]$["Name"] ["Description"|""]
     */
    public const PARAM = '~^(?:@param) +([^ ]*) *(?:(&|&\.{3}|\.{3}))?\$(\w+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]property["Variant"|""] ["Type"] $["Name"] ["Description"|""]
     */
    public const PROPERTY = '~^(?:@property(?:-(?:read|write))?) +([^ ]+) +\$(\w+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]return ["Type"] ["Description"|""]
     */
    public const RETURN = '~^(?:@return) +([^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]see ["File"|"FQSEN"] ["Description"|""]
     */
    public const SEE = '~^(?:@see) +([^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following formats:
     *
     * [at]since ["Version"] ["Description"|""]
     */
    public const SINCE = '~^(?:@since) +(\d[\d\.]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]throws ["Type"] ["Description"|""]
     */
    public const THROWS = '~^(?:@throws) +([^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]uses ["File"|"FQSEN"] ["Description"|""]
     */
    public const USES = '~^(?:@uses) +([^ ]+) *(.*)$~';

    /**
     * Pattern for parsing, matches following format:
     *
     * [at]var ["Type"] $["Name"|""] ["Description"|""]
     */
    public const VAR = '~^(?:@var) +([^ ]+)(?: +\$(\w+))? *(.*)$~';

    /**
     * Pattern for parsing, matches following formats:
     *
     * [at]version ["Version"] ["Description"|""]
     */
    public const VERSION = '~^(?:@version) +(?:([\d\.]+|\$.+\$|@.+@)) *(.*)$~';
}
