<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * It is a collection of nodes and edges that can be represented using DOT language
 *
 * @link https://en.wikipedia.org/wiki/DOT_(graph_description_language) See for more details about DOT language
 */
final class Digraph
{
    /** @var HasDotRepresentation[] */
    private array $elements;

    public function __construct()
    {
        $this->elements = [];
    }

    /** @param HasDotRepresentation[] $definitions */
    public function add(array $definitions): void
    {
        $this->elements = array_merge($this->elements, $definitions);
    }

    /** @return HasDotRepresentation[] */
    public function elements(): array
    {
        return $this->elements;
    }

    public function id(): string
    {
        return sha1((string) mt_rand());
    }
}
