<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

/**
 * It will show an empty row if the definition does not have methods or if it does not have
 * attributes
 */
class DefaultLabelStyle extends NodeLabelStyle
{
    protected function setPartials(): void
    {
        $this->attributes = '_attributes.html.twig';
        $this->methods = '_methods.html.twig';
    }
}
