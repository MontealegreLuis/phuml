<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Parser\Code\Builders\Members\FilteredAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;
use PhUml\Parser\Code\Builders\MembersBuilder as DefinitionMembersBuilder;

final class MembersBuilderBuilder
{
    public function build(): DefinitionMembersBuilder
    {
        $visibilityBuilder = new VisibilityBuilder();
        $visibilityFilters = new VisibilityFilters();
        $typeBuilder = A::typeBuilderBuilder()->build();
        return new DefinitionMembersBuilder(
            new FilteredConstantsBuilder($visibilityBuilder, $visibilityFilters),
            new FilteredAttributesBuilder($visibilityBuilder, $typeBuilder, $visibilityFilters),
            new FilteredMethodsBuilder(
                new ParametersBuilder($typeBuilder),
                $typeBuilder,
                $visibilityBuilder,
                $visibilityFilters
            )
        );
    }
}
