<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\Builders\TagName;
use PhUml\Parser\Code\Builders\TagType;
use PhUml\Parser\Code\Builders\TagTypeFactory;

final class TypeResolver
{
    public function __construct(private readonly TagTypeFactory $typeFactory)
    {
    }

    public function resolveFromDocBlock(
        ?string $docBlock,
        TagName $tagName,
        UseStatements $useStatements,
        callable $filter = null
    ): TypeDeclaration {
        if ($docBlock === null) {
            return TypeDeclaration::absent();
        }

        $returnType = $this->typeFactory->typeFromTag($docBlock, $tagName, $filter);

        return $returnType instanceof TagType ? $returnType->resolve($useStatements) : TypeDeclaration::absent();
    }
}
