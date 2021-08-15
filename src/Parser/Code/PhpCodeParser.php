<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Builders\Members\NoAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\NoConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\NoMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\TypeBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;
use PhUml\Parser\Code\Builders\MembersBuilder;
use PhUml\Parser\Code\Builders\TagTypeFactory;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;
use PhUml\Parser\Code\Builders\UseStatementsBuilder;
use PhUml\Parser\Code\Visitors\ClassVisitor;
use PhUml\Parser\Code\Visitors\InterfaceVisitor;
use PhUml\Parser\Code\Visitors\TraitVisitor;
use PhUml\Parser\CodeParserConfiguration;
use PhUml\Parser\SourceCode;

/**
 * It traverses the AST of all the files and interfaces found by the `CodeFinder` and builds a
 * `Codebase` object
 *
 * In order to create the collection of definitions it uses the following visitors
 *
 * - The `ClassVisitor` which builds `ClassDefinition`s
 * - The `InterfaceVisitor` which builds `InterfaceDefinition`s
 * - The `TraitVisitor` which builds `TraitDefinition`s
 */
final class PhpCodeParser
{
    public static function fromConfiguration(CodeParserConfiguration $configuration): PhpCodeParser
    {
        if ($configuration->hideAttributes()) {
            $constantsBuilder = new NoConstantsBuilder();
            $attributesBuilder = new NoAttributesBuilder();
        }
        if ($configuration->hideMethods()) {
            $methodsBuilder = new NoMethodsBuilder();
        }
        $filters = [];
        if ($configuration->hidePrivate()) {
            $filters[] = new PrivateVisibilityFilter();
        }
        if ($configuration->hideProtected()) {
            $filters[] = new ProtectedVisibilityFilter();
        }
        $visibilityBuilder = new VisibilityBuilder();
        $typeBuilder = new TypeBuilder(new TypeResolver(new TagTypeFactory(DocBlockFactory::createInstance())));
        $methodsBuilder ??= new ParsedMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            $visibilityBuilder,
        );
        $constantsBuilder ??= new ParsedConstantsBuilder($visibilityBuilder);
        $attributesBuilder ??= new ParsedAttributesBuilder($visibilityBuilder, $typeBuilder);
        $filters = new VisibilityFilters($filters);
        $membersBuilder = new MembersBuilder($constantsBuilder, $attributesBuilder, $methodsBuilder, $filters);
        $useStatementsBuilder = new UseStatementsBuilder();
        $classBuilder = new ClassDefinitionBuilder($membersBuilder, $useStatementsBuilder);
        $interfaceBuilder = new InterfaceDefinitionBuilder($membersBuilder, $useStatementsBuilder);
        $traitBuilder = new TraitDefinitionBuilder($membersBuilder, $useStatementsBuilder);

        $codebase = new Codebase();

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor(new NodeConnectingVisitor());
        $traverser->addVisitor(new ClassVisitor($classBuilder, $codebase));
        $traverser->addVisitor(new InterfaceVisitor($interfaceBuilder, $codebase));
        $traverser->addVisitor(new TraitVisitor($traitBuilder, $codebase));
        $traverser = new PhpTraverser($traverser, $codebase);

        return new self($parser, $traverser);
    }

    private function __construct(
        private Parser $parser,
        private PhpTraverser $traverser,
    ) {
    }

    public function parse(SourceCode $sourceCode): Codebase
    {
        foreach ($sourceCode->fileContents() as $code) {
            /** @var Stmt[] $nodes Since the parser is run in throw errors mode */
            $nodes = $this->parser->parse($code);
            $this->traverser->traverse($nodes);
        }
        return $this->traverser->codebase();
    }
}
