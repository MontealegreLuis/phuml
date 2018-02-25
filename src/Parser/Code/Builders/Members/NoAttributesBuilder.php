<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

/**
 * It will ignore the attributes of a definition, and therefore its filters
 */
class NoAttributesBuilder extends AttributesBuilder
{
    public function __construct()
    {
        parent::__construct([]);
    }

    public function build(array $definitionAttributes): array
    {
        return [];
    }
}
