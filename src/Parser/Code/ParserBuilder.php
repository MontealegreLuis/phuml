<?php
/**
 * PHP version 7.1
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
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;
use PhUml\Parser\Code\Builders\Members\NoAttributesBuilder;
use PhUml\Parser\Code\Builders\Members\NoConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\NoMethodsBuilder;

class ParserBuilder
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

    public function build(): PhpParser
    {
        $constantsBuilder = $this->constantsBuilder ?? new ConstantsBuilder();
        $methodsBuilder = $this->methodsBuilder ?? new MethodsBuilder($this->filters);
        $attributesBuilder = $this->attributesBuilder ?? new AttributesBuilder($this->filters);

        return new Php5Parser(
            new ClassDefinitionBuilder($constantsBuilder, $attributesBuilder, $methodsBuilder),
            new InterfaceDefinitionBuilder($constantsBuilder, $methodsBuilder)
        );
    }
}
