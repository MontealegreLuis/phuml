<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\StreamOutput;

class UpdaterDisplayTest extends TestCase
{
    /** @test */
    function it_displays_the_result_of_a_rollback()
    {
        $this->display->rollbackMessage(true);

        $message = $this->outputMessage();

        $this->assertRegExp('/phUML has been rolled back to prior version./', $message);
    }

    /** @test */
    function it_displays_the_result_of_a_failed_rollback()
    {
        $this->display->rollbackMessage(false);

        $this->assertRegExp('/Rollback failed for unknown reasons/', $this->outputMessage());
    }

    /** @test */
    function it_displays_the_current_local_version()
    {
        $version = '1.6.1';

        $this->display->currentLocalVersion($version);

        $this->assertRegExp("/current local (.)+ $version/", $this->outputMessage());
    }

    /** @test */
    function it_displays_the_new_available_version()
    {
        $version = '1.6.2';

        $this->display->newVersion($version);

        $this->assertRegExp("/available remotely is(.)+ $version/", $this->outputMessage());
    }

    /** @test */
    function it_displays_no_updates_are_available_message()
    {
        $this->display->noUpdatesAvailable();

        $this->assertRegExp('/no stable builds available/', $this->outputMessage());
    }

    /** @test */
    function it_displays_already_up_to_date_message()
    {
        $this->display->alreadyUpToDate();

        $this->assertRegExp('/current stable build installed/', $this->outputMessage());
    }

    /** @test */
    function it_displays_update_applied_message()
    {
        $oldVersion = '1.6.1';
        $newVersion = '1.6.2';

        $this->display->updateApplied($oldVersion, $newVersion);

        $message = $this->outputMessage();
        $this->assertRegExp("/current version is(.)+$newVersion/i", $message);
        $this->assertRegExp("/previous version was(.)+$oldVersion/i", $message);
    }

    /** @test */
    function it_displays_no_update_applied_message()
    {
        $version = '1.6.1';
        $this->display->noUpdateApplied($version);

        $message = $this->outputMessage();
        $this->assertRegExp('/currently up to date/', $message);
        $this->assertRegExp("/current version is(.)+$version/i", $message);
    }

    /** @test */
    function it_displays_exception_message()
    {
        $this->display->error(new Exception('Something went wrong'));

        $this->assertRegExp('/something went wrong/i', $this->outputMessage());
    }

    function outputMessage(): string
    {
        rewind($this->displayOutput->getStream());

        return stream_get_contents($this->displayOutput->getStream());
    }

    /** @before */
    function configureDisplay()
    {
        $this->displayOutput = new StreamOutput(fopen('php://memory', 'wb', false));
        $this->display = new UpdaterDisplay($this->displayOutput);
    }

    /** @var UpdaterDisplay */
    private $display;

    /** @var StreamOutput */
    private $displayOutput;
}
