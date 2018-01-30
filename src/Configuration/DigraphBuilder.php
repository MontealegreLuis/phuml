<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Graphviz\Builders\NodeLabelStyle;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\NoAttributesBuilder;
use PhUml\Parser\Raw\Builders\NoMethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\PhpParser;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

class DigraphBuilder
{
    /** @var DigraphConfiguration */
    protected $configuration;

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively() ? new CodeFinder() : new NonRecursiveCodeFinder();
    }

    protected function digraphProcessor(): GraphvizProcessor
    {
        $associationsBuilder = $this->configuration->extractAssociations() ? new EdgesBuilder() : new NoAssociationsBuilder();
        $digraphProcessor = new GraphvizProcessor(
            new ClassGraphBuilder($this->nodeLabelBuilder(), $associationsBuilder)
        );
        return $digraphProcessor;
    }

    protected function nodeLabelBuilder(): NodeLabelBuilder
    {
        return new NodeLabelBuilder(new TemplateEngine(), $this->nodeLabelStyle());
    }

    protected function nodeLabelStyle(): NodeLabelStyle
    {
        $attributes = $this->configuration->hideEmptyBlocks() ? '_empty-attributes.html.twig' : '_attributes.html.twig';
        $methods = $this->configuration->hideEmptyBlocks() ? '_empty-methods.html.twig' : '_methods.html.twig';

        return new NodeLabelStyle($attributes, $methods);
    }

    protected function codeParser(): CodeParser
    {
        return new CodeParser(new StructureBuilder(), $this->tokenParser());
    }

    protected function tokenParser(): PhpParser
    {
        return new Php5Parser(
            new RawDefinitions(),
            new RawClassBuilder($this->attributesBuilder(), $this->methodsBuilder()),
            new RawInterfaceBuilder($this->methodsBuilder())
        );
    }

    private function attributesBuilder(): AttributesBuilder
    {
        if ($this->configuration->hideAttributes()) {
            return new NoAttributesBuilder();
        }
        return new AttributesBuilder($this->filters());
    }

    private function methodsBuilder(): MethodsBuilder
    {
        if ($this->configuration->hideMethods()) {
            return new NoMethodsBuilder();
        }
        return new MethodsBuilder($this->filters());
    }

    /** @return MembersFilter[] */
    protected function filters(): array
    {
        $filters = [];
        if ($this->configuration->hidePrivate()) {
            $filters[] = new PrivateMembersFilter();
        }
        if ($this->configuration->hideProtected()) {
            $filters[] = new ProtectedMembersFilter();
        }
        return $filters;
    }
}
