<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\Codebase;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\Code\RelationshipsResolvers;

/**
 * It takes the files found by the `CodeFinder` and turns them into a `Codebase`
 *
 * A `Codebase` is a collection of `Definition`s (classes, interfaces and traits)
 *
 * It will call the `ExternalDefinitionsResolver` to add generic `Definition`s for classes,
 * interfaces and traits that do not belong directly to the current codebase
 *
 * These external definitions are either built-in or from third party libraries
 */
final class CodeParser
{
    public static function fromConfiguration(CodeParserConfiguration $configuration): CodeParser
    {
        $resolvers = $configuration->extractAssociations()
            ? RelationshipsResolvers::withAssociations()
            : RelationshipsResolvers::withoutAssociations();

        return new CodeParser(PhpCodeParser::fromConfiguration($configuration), $resolvers);
    }

    private function __construct(private PhpCodeParser $parser, private RelationshipsResolvers $resolvers)
    {
    }

    /**
     * The parsing process is as follows
     *
     * 1. Parse the code and populate the `Codebase` with definitions
     * 2. Add external definitions (built-in/third party), if needed
     */
    public function parse(SourceCode $sourceCode): Codebase
    {
        $codebase = $this->parser->parse($sourceCode);

        $this->resolvers->addExternalDefinitionsTo($codebase);

        return $codebase;
    }
}
