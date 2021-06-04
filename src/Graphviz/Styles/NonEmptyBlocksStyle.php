<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

/**
 * It will not create a row if the definition does not have methods or if it does not have
 * attributes
 */
final class NonEmptyBlocksStyle extends DigraphStyle
{
    protected function setPartials(): void
    {
        $this->attributes = 'partials/_empty-attributes.html.twig';
        $this->methods = 'partials/_empty-methods.html.twig';
    }
}
