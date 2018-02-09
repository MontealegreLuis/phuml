<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\ConstantsBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\NoAttributesBuilder;
use PhUml\Parser\Raw\Builders\NoConstantsBuilder;
use PhUml\Parser\Raw\Builders\NoMethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;

class ParserBuilder
{
    /** @var MembersFilter[] */
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
        $this->filters[] = new PrivateMembersFilter();

        return $this;
    }

    public function excludeProtectedMembers(): ParserBuilder
    {
        $this->filters[] = new ProtectedMembersFilter();

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
            new RawClassBuilder($constantsBuilder, $attributesBuilder, $methodsBuilder),
            new RawInterfaceBuilder($constantsBuilder, $methodsBuilder)
        );
    }
}
