<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\VisibilityFilter;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Builders\Members\AttributesBuilder;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\FilteredMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\NoAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\NoConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\NoMethodsBuilder;
use PhUml\Parser\Code\Builders\Members\ParametersBuilder;
use PhUml\Parser\Code\Builders\Members\TypeBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityBuilder;
use PhUml\Parser\Code\Builders\Members\VisibilityFilters;
use PhUml\Parser\Code\Builders\MembersBuilder;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;

final class ParserBuilder
{
    /** @var VisibilityFilter[] */
    private $filters;

    /** @var MethodsBuilder */
    private $methodsBuilder;

    /** @var ConstantsBuilder */
    private $constantsBuilder;

    /** @var AttributesBuilder */
    private $attributesBuilder;

    public function __construct()
    {
        $this->filters = [];
    }

    public function excludePrivateMembers(): ParserBuilder
    {
        $this->filters[] = new PrivateVisibilityFilter();

        return $this;
    }

    public function excludeProtectedMembers(): ParserBuilder
    {
        $this->filters[] = new ProtectedVisibilityFilter();

        return $this;
    }

    public function excludeMethods(): ParserBuilder
    {
        $this->methodsBuilder = new NoMethodsBuilder();

        return $this;
    }

    public function excludeAttributes(): ParserBuilder
    {
        $this->constantsBuilder = new NoConstantsBuilder();
        $this->attributesBuilder = new NoAttributesBuilder();

        return $this;
    }

    public function build(): PhpCodeParser
    {
        $visibilityBuilder = new VisibilityBuilder();
        $typeBuilder = new TypeBuilder();
        $filters = new VisibilityFilters($this->filters);
        $constantsBuilder = $this->constantsBuilder ?? new FilteredConstantsBuilder($visibilityBuilder, $filters);
        $methodsBuilder = $this->methodsBuilder ?? new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            $visibilityBuilder,
            $filters
        );
        $attributesBuilder = $this->attributesBuilder ?? new FilteredAttributesBuilder(
            $visibilityBuilder,
            $filters
        );
        $membersBuilder = new MembersBuilder($constantsBuilder, $attributesBuilder, $methodsBuilder);

        return new PhpCodeParser(
            new ClassDefinitionBuilder($membersBuilder),
            new InterfaceDefinitionBuilder($membersBuilder),
            new TraitDefinitionBuilder($membersBuilder)
        );
    }
}
