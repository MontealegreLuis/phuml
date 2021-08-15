<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Generators\StatisticsGeneratorConfiguration;
use Symfony\Component\Console\Output\NullOutput;

final class StatisticsGeneratorConfigurationBuilder
{
    /** @var mixed[]  */
    private array $overrides = [];

    private bool $recursive = false;

    public function recursive(): StatisticsGeneratorConfigurationBuilder
    {
        $this->recursive = true;
        return $this;
    }

    /** @param mixed[] $options */
    public function withOverriddenOptions(array $options): StatisticsGeneratorConfigurationBuilder
    {
        $this->overrides = $options;
        return $this;
    }

    public function build(): StatisticsGeneratorConfiguration
    {
        $options = array_merge([
            'recursive' => $this->recursive,
            'associations' => false,
            'hide-private' => false,
            'hide-protected' => false,
            'hide-attributes' => false,
            'hide-methods' => false,
        ], $this->overrides);

        return new StatisticsGeneratorConfiguration($options, new ConsoleProgressDisplay(new NullOutput()));
    }
}
