<?php
/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUndefinedClassInspection */

$finder = PhpCsFixer\Finder::create()
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        'array_syntax' => ['syntax' => 'short'],
        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_control_structures' => 'next',
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_anonymous_constructs' => 'next',
        ],
        'constant_case' => ['case' => 'lower'],
        'function_declaration' => ['closure_function_spacing' => 'none'],
        'linebreak_after_opening_tag' => true,
        'phpdoc_order' => true,
        'strict_param' => true,
        'string_line_ending' => true,
        'native_function_invocation' => [
            'include' => ['@all'],
        ],
        'global_namespace_import' => [
            'import_classes' => null,
            'import_constants' => true,
            'import_functions' => true,
        ]
    ])
    ->setFinder($finder);
