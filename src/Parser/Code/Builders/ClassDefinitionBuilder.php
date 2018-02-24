<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name as ClassDefinitionName;
use PhUml\Code\Name as TraitName;

/**
 * It builds a `ClassDefinition`
 *
 * @see MembersBuilder for more details
 */
class ClassDefinitionBuilder
{
    /** @var MembersBuilder */
    protected $membersBuilder;

    public function __construct(MembersBuilder $membersBuilder = null)
    {
        $this->membersBuilder = $membersBuilder ?? new MembersBuilder();
    }

    public function build(Class_ $class): ClassDefinition
    {
        return new ClassDefinition(
            ClassDefinitionName::from($class->name),
            $this->membersBuilder->methods($class->getMethods()),
            $this->membersBuilder->constants($class->stmts),
            !empty($class->extends) ? ClassDefinitionName::from(end($class->extends->parts)) : null,
            $this->membersBuilder->attributes($class->stmts),
            $this->buildInterfaces($class->implements),
            $this->buildTraits($class->stmts)
        );
    }

    /** @return ClassDefinitionName[] */
    protected function buildInterfaces(array $implements): array
    {
        return array_map(function (Name $name) {
            return ClassDefinitionName::from($name->getLast());
        }, $implements);
    }

    /** @param Node[] $nodes */
    protected function buildTraits(array $nodes): array
    {
        $useStatements = array_filter($nodes, function (Node $node) {
            return $node instanceof TraitUse;
        });

        if (empty($useStatements)) {
            return [];
        }

        $traits = [];
        /** @var TraitUse  $use */
        foreach ($useStatements as $use) {
            $traits = $this->traitNames($use, $traits);
        }

        return $traits;
    }

    /**
     * @param Name[] $traits
     * @return TraitName[]
     */
    protected function traitNames(TraitUse $use, array $traits): array
    {
        foreach ($use->traits as $name) {
            $traits[] = TraitName::from($name->getLast());
        }
        return $traits;
    }
}
