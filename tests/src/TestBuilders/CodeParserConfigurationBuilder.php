<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Parser\CodeParserConfiguration;

final class CodeParserConfigurationBuilder
{
    private bool $hideMethods = false;

    private bool $hideAttributes = false;

    private bool $hideProtected = false;

    private bool $hidePrivate = false;

    private bool $associations = false;

    public function build(): CodeParserConfiguration
    {
        return new CodeParserConfiguration([
            'associations' => $this->associations,
            'hide-private' => $this->hidePrivate,
            'hide-protected' => $this->hideProtected,
            'hide-attributes' => $this->hideAttributes,
            'hide-methods' => $this->hideMethods,
        ]);
    }

    public function withoutMethods(): CodeParserConfigurationBuilder
    {
        $this->hideMethods = true;
        return $this;
    }

    public function withoutAttributes(): CodeParserConfigurationBuilder
    {
        $this->hideAttributes = true;
        return $this;
    }

    public function withoutProtectedMembers(): CodeParserConfigurationBuilder
    {
        $this->hideProtected = true;
        return $this;
    }

    public function withoutPrivateMembers(): CodeParserConfigurationBuilder
    {
        $this->hidePrivate = true;
        return $this;
    }

    public function withAssociations(): CodeParserConfigurationBuilder
    {
        $this->associations = true;
        return $this;
    }
}
