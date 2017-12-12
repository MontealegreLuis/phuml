<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

class Node implements HasDotRepresentation
{
    /** @var HasNodeIdentifier */
    private $node;

    /** @var string */
    private $options;

    public function __construct(HasNodeIdentifier $node, string $htmlLabel)
    {
        $this->node = $node;
        $this->options = $this->buildOptionsUsing($htmlLabel);
    }

    public function toDotLanguage(): string
    {
        return "\"{$this->node->identifier()}\" {$this->options}\n";
    }

    private function buildOptionsUsing(string $htmlLabel): string
    {
        return "[label={$htmlLabel} shape=plaintext]";
    }
}
