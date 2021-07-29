<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Class_;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name as ClassDefinitionName;
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

    public function __construct(private MembersBuilder $membersBuilder)
    {
    }

    public function build(Class_ $class): ClassDefinition
    {
        return new ClassDefinition(
            new ClassDefinitionName((string) $class->name),
            $this->membersBuilder->methods($class->getMethods()),
            $this->membersBuilder->constants($class->stmts),
            $class->extends !== null ? new ClassDefinitionName((string) end($class->extends->parts)) : null,
            $this->membersBuilder->attributes($class->stmts, $class->getMethod('__construct')),
            $this->buildInterfaces($class->implements),
            $this->buildTraits($class->stmts)
        );
    }
}
