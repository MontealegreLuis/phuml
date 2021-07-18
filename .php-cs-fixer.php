<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src']);

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@PSR12' => true,
        'no_unused_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_blank_line_before_namespace' => true,
        'ordered_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'array_indentation' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'no_whitespace_in_blank_line' => true,
        'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']],
        'cast_spaces' => ['space' => 'single'],
        'single_blank_line_at_eof' => true,
        'not_operator_with_successor_space' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'return_type_declaration' => ['space_before' => 'none'],
        'modernize_types_casting' => true,
        'blank_line_after_opening_tag' => false,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => 'PHP version 8.0

This source file is subject to the license that is bundled with this package in the file LICENSE.',
            'comment_type' => 'PHPDoc',
            'location' => 'after_declare_strict',
            'separate' => 'bottom'
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
