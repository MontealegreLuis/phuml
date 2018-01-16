<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * It is a collection of nodes and edges that can be represented using DOT language
 *
 * @link https://en.wikipedia.org/wiki/DOT_(graph_description_language) See for more details about DOT language
 */
class Digraph implements HasDotRepresentation
{
    /** @var HasDotRepresentation[] */
    private $dotElements;

    public function __construct()
    {
        $this->dotElements = [];
    }

    /** @param HasDotRepresentation[] */
    public function add(array $definitions): void
    {
        $this->dotElements = array_merge($this->dotElements, $definitions);
    }

    public function toDotLanguage(): string
    {
        return "digraph \"{$this->graphId()}\" {
splines = true;
overlap = false;
mindist = 0.6;
{$this->elementsToDotLanguage()}}";
    }

    private function elementsToDotLanguage(): string
    {
        $dotFormat = array_map(function (HasDotRepresentation $element) {
            return $element->toDotLanguage();
        }, $this->dotElements);

        return implode('', $dotFormat);
    }

    private function graphId(): string
    {
        return sha1(mt_rand());
    }
}
