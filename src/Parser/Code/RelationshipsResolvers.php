<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;

final class RelationshipsResolvers
{
    public static function withAssociations(): RelationshipsResolvers
    {
        return new RelationshipsResolvers([new ExternalDefinitionsResolver(), new ExternalAssociationsResolver()]);
    }

    public static function withoutAssociations(): RelationshipsResolvers
    {
        return new RelationshipsResolvers([new ExternalDefinitionsResolver()]);
    }

    public function addExternalDefinitionsTo(Codebase $codebase): void
    {
        array_map(static fn (RelationshipsResolver $resolver) => $resolver->resolve($codebase), $this->resolvers);
    }

    /** @param RelationshipsResolver[] $resolvers */
    private function __construct(private array $resolvers)
    {
    }
}
