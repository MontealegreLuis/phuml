<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

/**
 * It will show an empty row if the definition does not have methods or if it does not have
 * attributes
 */
final class DefaultDigraphStyle extends DigraphStyle
{
    protected function setPartials(): void
    {
        $this->attributes = 'partials/_attributes.html.twig';
        $this->methods = 'partials/_methods.html.twig';
    }
}
