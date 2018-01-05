<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PhUml\Code\Structure;

class TokenParser
{
    /** @var array */
    private $structure;

    /** @var int */
    private $lastToken;

    /** @var StructureBuilder */
    private $builder;

    /** @var Definitions */
    private $definitions;

    /** @var RelationsResolver */
    private $resolver;

    public function __construct(
        Definitions $definitions = null,
        RelationsResolver $resolver = null,
        StructureBuilder $builder = null
    ) {
        $this->builder = $builder ?? new StructureBuilder();
        $this->definitions = $definitions ?? new Definitions();
        $this->resolver = $resolver ?? new RelationsResolver();
    }

    public function parse(CodeFinder $finder): Structure
    {
        foreach ($finder->files() as $code) {
            $this->initParserAttributes();
            $this->process(token_get_all($code));
            $this->storeClassOrInterface();
        }
        $this->resolver->resolve($this->definitions);
        return $this->builder->buildFromDefinitions($this->definitions);
    }

    private function process(array $tokens): void
    {
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $this->processComplex(...$token);
            } else {
                $this->processSimple($token);
            }
        }
    }

    private function processSimple(string $token): void
    {
        switch ($token) {
            case '(':
                break;
            case ',':
                $this->resetTypeHint();
                break;
            case '=':
                $this->resetToken();
                break;
            case ')':
                $this->saveMethodDefinition();
                break;
            default:
                // Ignore everything else
                $this->lastToken = null;
        }
    }

    private function processComplex(int $type, string $value): void
    {
        switch ($type) {
            case T_WHITESPACE:
                break;
            case T_VAR:
            case T_ARRAY:
            case T_CONSTANT_ENCAPSED_STRING:
            case T_LNUMBER:
            case T_DNUMBER:
            case T_PAAMAYIM_NEKUDOTAYIM:
                $this->resetToken();
                break;
            case T_FUNCTION:
                $this->startMethodDefinition($type);
                break;
            case T_INTERFACE:
            case T_CLASS:
                $this->startClassOrInterfaceDefinition($type);
                break;
            case T_IMPLEMENTS:
            case T_EXTENDS:
                $this->startExtendsOrImplementsDeclaration($type);
                break;
            case T_VARIABLE:
                $this->saveAttributeOrParameter($value);
                break;
            case T_STRING:
                $this->saveIdentifier($value);
                break;
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->saveModifier($type, $value);
                break;
            case T_DOC_COMMENT:
                $this->saveDocBlock($value);
                break;
            default:
                // Ignore everything else
                $this->lastToken = null;
                // And reset the docblock
                $this->structure['docblock'] = null;
        }
    }

    private function initParserAttributes(): void
    {
        $this->structure = [
            'class' => null,
            'interface' => null,
            'function' => null,
            'attributes' => [],
            'functions' => [],
            'typehint' => null,
            'params' => [],
            'implements' => [],
            'extends' => null,
            'modifier' => 'public',
            'docblock' => null,
        ];

        $this->lastToken = [];
    }

    private function resetTypeHint(): void
    {
        $this->structure['typehint'] = null;
    }

    private function resetToken(): void
    {
        if ($this->lastToken !== T_FUNCTION) {
            $this->lastToken = null;
        }
    }

    private function startMethodDefinition(int $type): void
    {
        switch ($this->lastToken) {
            case null:
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->lastToken = $type;
                break;
            default:
                $this->lastToken = null;
        }
    }

    private function startClassOrInterfaceDefinition(int $type): void
    {
        if ($this->lastToken === null) {
            // New initial interface or class token
            // Store the class or interface definition if there is any in the
            // parser arrays ( There might be more than one class/interface per
            // file )
            $this->storeClassOrInterface();

            // Remember the last token
            $this->lastToken = $type;
        } else {
            $this->lastToken = null;
        }
    }

    private function startExtendsOrImplementsDeclaration(int $type): void
    {
        if ($this->lastToken === null) {
            $this->lastToken = $type;
        } else {
            $this->lastToken = null;
        }
    }

    private function saveMethodDefinition(): void
    {
        if ($this->lastToken === T_FUNCTION) {
            // The function declaration has been closed

            // Add the current function
            $this->structure['functions'][] = [
                $this->structure['function'],
                $this->structure['modifier'],
                $this->structure['params'],
                $this->structure['docblock']
            ];
            // Reset the last token
            $this->lastToken = null;
            //Reset the modifier state
            $this->structure['modifier'] = 'public';
            // Reset the params array
            $this->structure['params'] = [];
            $this->structure['typehint'] = null;
            // Reset the function name
            $this->structure['function'] = null;
            // Reset the docblock
            $this->structure['docblock'] = null;
        } else {
            $this->lastToken = null;
        }
    }

    private function saveAttributeOrParameter(string $identifier): void
    {
        switch ($this->lastToken) {
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                // A new class attribute
                $this->structure['attributes'][] = [
                    $identifier,
                    $this->structure['modifier'],
                    $this->structure['docblock'],
                ];
                $this->lastToken = null;
                $this->structure['modifier'] = 'public';
                $this->structure['docblock'] = null;
                break;
            case T_FUNCTION:
                // A new function parameter
                $this->structure['params'][] = [
                    $this->structure['typehint'],
                    $identifier,
                ];
                break;
        }
    }

    private function saveIdentifier(string $identifier): void
    {
        switch ($this->lastToken) {
            case T_IMPLEMENTS:
                // Add interface to implements array
                $this->structure['implements'][] = $identifier;
                // We do not reset the last token here, because
                // there might be multiple interfaces
                break;
            case T_EXTENDS:
                // Set the superclass
                $this->structure['extends'] = $identifier;
                // Reset the last token
                $this->lastToken = null;
                break;
            case T_FUNCTION:
                // Add the current function only if there is no function name already
                // Because if we know the function name already this is a type hint
                if ($this->structure['function'] === null) {
                    // Function name
                    $this->structure['function'] = $identifier;
                } else {
                    // Type hint
                    $this->structure['typehint'] = $identifier;
                }
                break;
            case T_CLASS:
                // Set the class name
                $this->structure['class'] = $identifier;
                // Reset the last token
                $this->lastToken = null;
                break;
            case T_INTERFACE:
                // Set the interface name
                $this->structure['interface'] = $identifier;
                // Reset the last Token
                $this->lastToken = null;
                break;
            default:
                $this->lastToken = null;
        }
    }

    private function saveModifier(int $type, string $modifier): void
    {
        if ($this->lastToken === null) {
            $this->lastToken = $type;
            $this->structure['modifier'] = $modifier;
        } else {
            $this->lastToken = null;
        }
    }

    private function saveDocBlock(string $comment): void
    {
        if ($this->lastToken === null) {
            $this->structure['docblock'] = $comment;
        } else {
            $this->lastToken = null;
            $this->structure['docblock'] = null;
        }
    }

    private function storeClassOrInterface(): void
    {
        $this->definitions->add($this->structure);
        $this->initParserAttributes();
    }
}
