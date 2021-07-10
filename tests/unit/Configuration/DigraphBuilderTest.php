<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;
use PhUml\Parser\CodebaseDirectory;

final class DigraphBuilderTest extends TestCase
{
    /** @test */
    function it_builds_a_code_finder_that_searches_recursively()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => true,
            'associations' => 'true',
            'hide-private' => true,
            'hide-protected' => 0,
            'hide-attributes' => 1,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor');
        $codeFinder = $builder->codeFinder();
        $sourceCode = $codeFinder->find($directory);

        $this->assertCount(2, $sourceCode->fileContents());
    }

    /** @test */
    function it_builds_a_code_finder_that_does_not_search_recursively()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => false,
            'associations' => 'true',
            'hide-private' => true,
            'hide-protected' => 0,
            'hide-attributes' => 1,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor');
        $codeFinder = $builder->codeFinder();
        $sourceCode = $codeFinder->find($directory);

        $this->assertCount(1, $sourceCode->fileContents());
    }

    /** @test */
    function it_builds_a_parser_that_excludes_attributes()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => true,
            'hide-protected' => 0,
            'hide-attributes' => true,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor/graphviz');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(1, $codebase->definitions());
        $className = new Name('plGraphvizProcessorStyle');
        $this->assertTrue($codebase->has($className));
        $this->assertFalse($codebase->get($className)->hasAttributes());
    }

    /** @test */
    function it_builds_a_parser_that_includes_attributes()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => 0,
            'hide-protected' => 0,
            'hide-attributes' => false,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor/graphviz');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(1, $codebase->definitions());
        $className = new Name('plGraphvizProcessorStyle');
        $this->assertTrue($codebase->has($className));
        $this->assertTrue($codebase->get($className)->hasAttributes());
    }

    /** @test */
    function it_builds_a_parser_that_excludes_methods()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => [],
            'hide-protected' => 0,
            'hide-attributes' => false,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor/graphviz');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(1, $codebase->definitions());
        $className = new Name('plGraphvizProcessorStyle');
        $this->assertTrue($codebase->has($className));
        $this->assertEmpty($codebase->get($className)->methods());
    }

    /** @test */
    function it_builds_a_parser_that_includes_methods()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => [],
            'hide-protected' => 0,
            'hide-attributes' => null,
            'hide-methods' => false,
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/interfaces/processor/graphviz');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(1, $codebase->definitions());
        $className = new Name('plGraphvizProcessorStyle');
        $this->assertTrue($codebase->has($className));
        $this->assertCount(1, $codebase->get($className)->methods());
    }

    /** @test */
    function it_builds_a_parser_that_excludes_protected_members()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => [],
            'hide-protected' => true,
            'hide-attributes' => false,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions/base');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(2, $codebase->definitions());
        $className = new Name('plBasePropertyException');
        $this->assertTrue($codebase->has($className));
        /** @var ClassDefinition $definition */
        $definition = $codebase->get($className);
        $this->assertCount(3, $definition->constants());
    }

    /** @test */
    function it_builds_a_parser_that_includes_protected_members()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => [],
            'hide-protected' => false,
            'hide-attributes' => null,
            'hide-methods' => 'true',
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions/base');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(2, $codebase->definitions());
        $className = new Name('plBasePropertyException');
        $this->assertTrue($codebase->has($className));
        /** @var ClassDefinition $definition */
        $definition = $codebase->get($className);
        $this->assertCount(4, $definition->constants());
    }

    /** @test */
    function it_builds_a_parser_that_includes_relationships_from_attributes_and_constructor_parameters()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => true,
            'hide-private' => 0,
            'hide-protected' => null,
            'hide-attributes' => [],
            'hide-methods' => false,
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes/processor/graphviz/style');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(3, $codebase->definitions());
        $this->assertTrue($codebase->has(new Name('plStyleName')));
    }

    /** @test */
    function it_builds_a_parser_that_excludes_relationships_from_attributes_and_constructor_parameters()
    {
        $configuration = new DigraphConfiguration([
            'recursive' => 0,
            'associations' => false,
            'hide-private' => 0,
            'hide-protected' => null,
            'hide-attributes' => [],
            'hide-methods' => false,
            'hide-empty-blocks' => '',
            'theme' => 'phuml',
        ]);
        $builder = new DigraphBuilder($configuration);
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes/processor/graphviz/style');
        $finder = $builder->codeFinder();
        $parser = $builder->codeParser();
        $sourceCode = $finder->find($directory);

        $codebase = $parser->parse($sourceCode);

        $this->assertCount(2, $codebase->definitions());
        $this->assertFalse($codebase->has(new Name('plStyleName')));
    }
}
