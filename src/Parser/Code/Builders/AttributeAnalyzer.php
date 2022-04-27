<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use Attribute;
use PhpParser\Node\Stmt\Class_;

final class AttributeAnalyzer
{
    public function isAttribute(Class_ $class): bool
    {
        return $class->attrGroups !== []
            && $class->attrGroups[0]->attrs !== []
            && (string) $class->attrGroups[0]->attrs[0]->name === Attribute::class;
    }
}
