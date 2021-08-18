<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CodeParserConfigurationTest extends TestCase
{
    /** @test */
    function it_expects_extract_associations_option_to_be_a_boolean()
    {
        $this->options['associations'] = 0;

        $this->expectException(InvalidArgumentException::class);
        new CodeParserConfiguration($this->options);
    }

    /** @test */
    function it_expects_hide_private_members_option_to_be_a_boolean()
    {
        $this->options['hide-private'] = '';

        $this->expectException(InvalidArgumentException::class);
        new CodeParserConfiguration($this->options);
    }

    /** @test */
    function it_expects_hide_protected_members_option_to_be_a_boolean()
    {
        $this->options['hide-protected'] = '';

        $this->expectException(InvalidArgumentException::class);
        new CodeParserConfiguration($this->options);
    }

    /** @test */
    function it_expects_hide_attributes_option_to_be_a_boolean()
    {
        $this->options['hide-attributes'] = '1';

        $this->expectException(InvalidArgumentException::class);
        new CodeParserConfiguration($this->options);
    }

    /** @test */
    function it_expects_hide_methods_option_to_be_a_boolean()
    {
        $this->options['hide-methods'] = '1';

        $this->expectException(InvalidArgumentException::class);
        new CodeParserConfiguration($this->options);
    }

    /** @before */
    function let()
    {
        $this->options = [
            'associations' => false,
            'hide-private' => false,
            'hide-protected' => false,
            'hide-attributes' => false,
            'hide-methods' => false,
        ];
    }

    /** @var boolean[] */
    private array $options;
}
