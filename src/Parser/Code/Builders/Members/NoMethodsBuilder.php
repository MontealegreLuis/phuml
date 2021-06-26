<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

/**
 * It will ignore the methods of a definition. It will produce diagrams without methods.
 */
final class NoMethodsBuilder extends MethodsBuilder
{
    public function __construct()
    {
        parent::__construct(new ParametersBuilder(new TypeBuilder()), new TypeBuilder(), new VisibilityBuilder(), []);
    }

    public function build(array $methods): array
    {
        return [];
    }
}
