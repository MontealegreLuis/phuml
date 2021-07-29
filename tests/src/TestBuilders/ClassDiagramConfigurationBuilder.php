<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Generators\ClassDiagramConfiguration;
use Symfony\Component\Console\Output\NullOutput;

final class ClassDiagramConfigurationBuilder
{
    private bool $hideEmptyBlocks = false;

    private bool $hideAttributes = false;

    private bool $hideMethods = false;

    private bool $recursive = false;

    private bool $associations = false;

    private string $theme = 'phuml';

    private string $processor = 'dot';

    public function withoutEmptyBlocks(): ClassDiagramConfigurationBuilder
    {
        $this->hideEmptyBlocks = true;
        return $this;
    }

    public function withoutAttributes(): ClassDiagramConfigurationBuilder
    {
        $this->hideAttributes = true;
        return $this;
    }

    public function withoutMethods(): ClassDiagramConfigurationBuilder
    {
        $this->hideMethods = true;
        return $this;
    }

    public function recursive(): ClassDiagramConfigurationBuilder
    {
        $this->recursive = true;
        return $this;
    }

    public function withAssociations(): ClassDiagramConfigurationBuilder
    {
        $this->associations = true;
        return $this;
    }

    public function withTheme(string $theme): ClassDiagramConfigurationBuilder
    {
        $this->theme = $theme;
        return $this;
    }

    public function usingNeato(): ClassDiagramConfigurationBuilder
    {
        $this->processor = 'neato';
        return $this;
    }

    public function build(): ClassDiagramConfiguration
    {
        return new ClassDiagramConfiguration(
            [
                'recursive' => $this->recursive,
                'associations' => $this->associations,
                'hide-private' => false,
                'hide-protected' => false,
                'hide-attributes' => $this->hideAttributes,
                'hide-methods' => $this->hideMethods,
                'theme' => $this->theme,
                'hide-empty-blocks' => $this->hideEmptyBlocks,
                'processor' => $this->processor,
            ],
            new ConsoleProgressDisplay(new NullOutput())
        );
    }
}
