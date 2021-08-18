<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhUml\Code\UseStatements;

/**
 * It will ignore the attributes of a definition, and therefore its filters
 */
final class NoAttributesBuilder implements AttributesBuilder
{
    public function build(array $parsedAttributes, UseStatements $useStatements): array
    {
        return [];
    }

    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array
    {
        return [];
    }
}
