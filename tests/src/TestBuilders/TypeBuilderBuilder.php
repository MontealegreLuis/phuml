<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use phpDocumentor\Reflection\DocBlockFactory;
use PhUml\Parser\Code\Builders\Members\TypeBuilder;
use PhUml\Parser\Code\Builders\TagTypeFactory;
use PhUml\Parser\Code\TypeResolver;

final class TypeBuilderBuilder
{
    public function build(): TypeBuilder
    {
        return new TypeBuilder(new TypeResolver(new TagTypeFactory(DocBlockFactory::createInstance())));
    }
}
