<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

class SubGraph
{
    /** @var string */
    private $namespace;

    /** @var Node[] */
    private $nodes;

    public static function for($namespace): SubGraph
    {
        return new SubGraph($namespace);
    }

    public function add(Node $node): void
    {
        $this->nodes[] = $node;
    }

    /** @return Node[] */
    public function nodes(): array
    {
        return $this->nodes;
    }

    public function label(): string
    {
        return strtolower(str_replace('\\', '.', $this->namespace));
    }

    public function clusterId(): string
    {
        return strtolower('cluster_' . str_replace('\\', '_', $this->namespace));
    }

    private function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $this->nodes = [];
    }
}
