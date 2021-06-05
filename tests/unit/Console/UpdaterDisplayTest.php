<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use Exception;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\TextInMemoryOutput;

final class UpdaterDisplayTest extends TestCase
{
    /** @test */
    function it_displays_the_result_of_a_rollback()
    {
        $this->display->rollbackMessage(true);

        $message = $this->displayOutput->output();

        $this->assertRegExp('/phUML has been rolled back to prior version./', $message);
    }

    /** @test */
    function it_displays_the_result_of_a_failed_rollback()
    {
        $this->display->rollbackMessage(false);

        $this->assertRegExp('/Rollback failed for unknown reasons/', $this->displayOutput->output());
    }

    /** @test */
    function it_displays_the_current_local_version()
    {
        $version = '1.6.1';

        $this->display->currentLocalVersion($version);

        $this->assertRegExp("/current local (.)+$version/", $this->displayOutput->output());
    }

    /** @test */
    function it_displays_the_new_available_version()
    {
        $version = '1.6.2';

        $this->display->newVersion($version);

        $this->assertRegExp("/available remotely is(.)+$version/", $this->displayOutput->output());
    }

    /** @test */
    function it_displays_no_updates_are_available_message()
    {
        $this->display->noUpdatesAvailable();

        $this->assertRegExp('/no stable builds available/', $this->displayOutput->output());
    }

    /** @test */
    function it_displays_already_up_to_date_message()
    {
        $this->display->alreadyUpToDate();

        $this->assertRegExp('/current stable build installed/', $this->displayOutput->output());
    }

    /** @test */
    function it_displays_update_applied_message()
    {
        $oldVersion = '1.6.1';
        $newVersion = '1.6.2';

        $this->display->updateApplied($oldVersion, $newVersion);

        $message = $this->displayOutput->output();
        $this->assertRegExp("/current version is(.)+$newVersion/i", $message);
        $this->assertRegExp("/previous version was(.)+$oldVersion/i", $message);
    }

    /** @test */
    function it_displays_no_update_applied_message()
    {
        $version = '1.6.1';
        $this->display->noUpdateApplied($version);

        $message = $this->displayOutput->output();
        $this->assertRegExp('/currently up to date/', $message);
        $this->assertRegExp("/current version is(.)+$version/i", $message);
    }

    /** @test */
    function it_displays_exception_message()
    {
        $this->display->error(new Exception('Something went wrong'));

        $this->assertRegExp('/something went wrong/i', $this->displayOutput->output());
    }

    /** @before */
    function let()
    {
        $this->displayOutput = new TextInMemoryOutput();
        $this->display = new UpdaterDisplay($this->displayOutput);
    }

    /** @var UpdaterDisplay */
    private $display;

    /** @var TextInMemoryOutput */
    private $displayOutput;
}
