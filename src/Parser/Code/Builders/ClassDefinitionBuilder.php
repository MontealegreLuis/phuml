<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Class_;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;
use PhUml\Parser\Code\Builders\Names\InterfaceNamesBuilder;
use PhUml\Parser\Code\Builders\Names\TraitNamesBuilder;

/**
 * It builds a `ClassDefinition`
 *
 * @see MembersBuilder
 * @see InterfaceNamesBuilder
 * @see TraitNamesBuilder
 */
final class ClassDefinitionBuilder
{
    use InterfaceNamesBuilder;
    use TraitNamesBuilder;

    public function __construct(
        private readonly MembersBuilder $membersBuilder,
        private readonly UseStatementsBuilder $useStatementsBuilder,
        private readonly AttributeAnalyzer $analyzer
    ) {
    }

    public function build(Class_ $class): ClassDefinition
    {
        $useStatements = $this->useStatementsBuilder->build($class);

        return new ClassDefinition(
            new Name((string) $class->namespacedName),
            $this->membersBuilder->methods($class->getMethods(), $useStatements),
            $this->membersBuilder->constants($class->stmts),
            $class->extends !== null ? new Name((string) $class->extends) : null,
            $this->membersBuilder->properties($class->stmts, $class->getMethod('__construct'), $useStatements),
            $this->buildInterfaces($class->implements),
            $this->buildTraits($class->stmts),
            $this->analyzer->isAttribute($class)
        );
    }
}
