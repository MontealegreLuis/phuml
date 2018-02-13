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
class DefaultLabelStyle extends DigraphStyle
{
    protected function setPartials(): void
    {
        $this->attributes = 'partials/_attributes.html.twig';
        $this->methods = 'partials/_methods.html.twig';
    }
}
