<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhpParser\Node\Stmt\Class_;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;

class NumericIdClassDefinitionBuilder extends ClassDefinitionBuilder
{
    public function build(Class_ $class): ClassDefinition
    {
        return new NumericIdClass(
            Name::from($class->name),
            $this->membersBuilder->methods($class->getMethods()),
            $this->membersBuilder->constants($class->stmts),
            !empty($class->extends) ? Name::from(end($class->extends->parts)) : null,
            $this->membersBuilder->attributes($class->stmts),
            $this->buildInterfaces($class->implements),
            $this->buildTraits($class->stmts)
        );
    }
}
