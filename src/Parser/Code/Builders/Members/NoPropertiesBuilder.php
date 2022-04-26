<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhUml\Code\UseStatements;

/**
 * It will ignore the properties of a definition, and therefore its filters
 */
final class NoPropertiesBuilder implements PropertiesBuilder
{
    public function build(array $parsedProperties, UseStatements $useStatements): array
    {
        return [];
    }

    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array
    {
        return [];
    }
}
