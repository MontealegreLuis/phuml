<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\VisibilityFilter;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParsedPropertiesBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;
use PhUml\Parser\Code\Builders\MembersBuilder as DefinitionMembersBuilder;
use PhUml\Parser\Code\Builders\ParameterTagFilterFactory;

final class MembersBuilderBuilder
{
    /** @var VisibilityFilter[]  */
    private array $filters = [];

    public function excludePrivateMembers(): MembersBuilderBuilder
    {
        $this->filters[] = new PrivateVisibilityFilter();
        return $this;
    }

    public function excludeProtectedMembers(): MembersBuilderBuilder
    {
        $this->filters[] = new ProtectedVisibilityFilter();
        return $this;
    }

    public function build(): DefinitionMembersBuilder
    {
        $visibilityBuilder = new VisibilityBuilder();
        $typeBuilder = A::typeBuilderBuilder()->build();
        $parameterFilter = new ParameterTagFilterFactory();
        return new DefinitionMembersBuilder(
            new ParsedConstantsBuilder($visibilityBuilder),
            new ParsedPropertiesBuilder($visibilityBuilder, $typeBuilder, $parameterFilter),
            new ParsedMethodsBuilder(
                new ParametersBuilder($typeBuilder, $parameterFilter),
                $typeBuilder,
                $visibilityBuilder,
            ),
            new VisibilityFilters($this->filters),
        );
    }
}
