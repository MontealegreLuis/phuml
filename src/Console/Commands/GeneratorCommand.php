<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Console\ProgressDisplay;
use Symfony\Component\Console\Command\Command;

/**
 * All commands provide visual feedback about the progress of the current task to the user
 *
 * @see ProcessorProgressDisplay for the details about the feedback provided by the display
 */
class GeneratorCommand extends Command
{
    /** @var ProgressDisplay */
    protected $display;

    public function __construct(ProgressDisplay $display)
    {
        parent::__construct();
        $this->display = $display;
    }
}
