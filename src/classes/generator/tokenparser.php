<?php

use PhUml\Code\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Method;
use PhUml\Code\Variable;

class plStructureTokenparserGenerator extends plStructureGenerator
{
    /** @var ClassDefinition[] */
    private $classes;

    /** @var InterfaceDefinition[] */
    private $interfaces;

    /** @var array */
    private $parserStruct;

    /** @var int */
    private $lastToken;

    private function initGlobalAttributes()
    {
        $this->classes = [];
        $this->interfaces = [];
    }

    private function initParserAttributes()
    {
        $this->parserStruct = [
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

    public function createStructure(array $files)
    {
        $this->initGlobalAttributes();

        foreach ($files as $file) {
            $this->initParserAttributes();
            $tokens = token_get_all(file_get_contents($file));

            // Loop through all tokens
            foreach ($tokens as $token) {
                // Split into Simple and complex token
                if (!is_array($token)) {
                    switch ($token) {
                        case ',':
                            $this->comma();
                            break;

                        case '(':
                            $this->opening_bracket();
                            break;

                        case ')':
                            $this->closing_bracket();
                            break;

                        case '=':
                            $this->resetToken();
                            break;
                        default:
                            // Ignore everything else
                            $this->lastToken = null;
                    }
                } else {
                    switch ($token[0]) {
                        case T_WHITESPACE:
                            $this->t_whitespace($token);
                            break;

                        case T_FUNCTION:
                            $this->t_function($token);
                            break;

                        case T_VAR:
                        case T_ARRAY:
                        case T_CONSTANT_ENCAPSED_STRING:
                        case T_LNUMBER:
                        case T_DNUMBER:
                        case T_PAAMAYIM_NEKUDOTAYIM:
                            $this->resetToken();
                            break;

                        case T_VARIABLE:
                            $this->t_variable($token);
                            break;

                        case T_STRING:
                            $this->t_string($token);
                            break;

                        case T_INTERFACE:
                        case T_CLASS:
                            $this->startClassOrInterfaceDefinition($token);
                            break;

                        case T_IMPLEMENTS:
                        case T_EXTENDS:
                            $this->startExtendsOrImplementsDeclaration($token);
                            break;

                        case T_PUBLIC:
                            $this->t_public($token);
                            break;

                        case T_PROTECTED:
                            $this->t_protected($token);
                            break;

                        case T_PRIVATE:
                            $this->t_private($token);
                            break;

                        case T_DOC_COMMENT:
                            $this->t_doc_comment($token);
                            break;

                        default:
                            // Ignore everything else
                            $this->lastToken = null;
                            // And reset the docblock
                            $this->parserStruct['docblock'] = null;
                    }
                }
            }
            // One file is completely scanned here

            // Store interface or class in the parser arrays
            $this->storeClassOrInterface();
        }

        // Fix the class and interface connections
        $this->fixObjectConnections();

        // Return the class and interface structure
        return array_merge($this->classes, $this->interfaces);
    }

    private function comma()
    {
        // Reset typehints on each comma
        $this->parserStruct['typehint'] = null;
    }

    private function opening_bracket()
    {
        // Ignore opening brackets
    }

    private function closing_bracket()
    {
        if ($this->lastToken === T_FUNCTION) {
            // The function declaration has been closed

            // Add the current function
            $this->parserStruct['functions'][] = [
                $this->parserStruct['function'],
                $this->parserStruct['modifier'],
                $this->parserStruct['params'],
                $this->parserStruct['docblock']
            ];
            // Reset the last token
            $this->lastToken = null;
            //Reset the modifier state
            $this->parserStruct['modifier'] = 'public';
            // Reset the params array
            $this->parserStruct['params'] = [];
            $this->parserStruct['typehint'] = null;
            // Reset the function name
            $this->parserStruct['function'] = null;
            // Reset the docblock
            $this->parserStruct['docblock'] = null;
        } else {
            $this->lastToken = null;
        }
    }

    private function resetToken(): void
    {
        if ($this->lastToken !== T_FUNCTION) {
            $this->lastToken = null;
        }
    }

    private function t_whitespace($token)
    {
        // Ignore whitespaces
    }

    private function t_function($token)
    {
        switch ($this->lastToken) {
            case null:
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->lastToken = $token[0];
                break;
            default:
                $this->lastToken = null;
        }
    }

    private function t_variable($token)
    {
        switch ($this->lastToken) {
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                // A new class attribute
                $this->parserStruct['attributes'][] = [
                    $token[1],
                    $this->parserStruct['modifier'],
                    $this->parserStruct['docblock'],
                ];
                $this->lastToken = null;
                $this->parserStruct['modifier'] = 'public';
                $this->parserStruct['docblock'] = null;
                break;
            case T_FUNCTION:
                // A new function parameter
                $this->parserStruct['params'][] = [
                    $this->parserStruct['typehint'],
                    $token[1]
                ];
                break;
        }
    }

    private function t_string($token)
    {
        switch ($this->lastToken) {
            case T_IMPLEMENTS:
                // Add interface to implements array
                $this->parserStruct['implements'][] = $token[1];
                // We do not reset the last token here, because
                // there might be multiple interfaces
                break;
            case T_EXTENDS:
                // Set the superclass
                $this->parserStruct['extends'] = $token[1];
                // Reset the last token
                $this->lastToken = null;
                break;
            case T_FUNCTION:
                // Add the current function only if there is no function name already
                // Because if we know the function name already this is a type hint
                if ($this->parserStruct['function'] === null) {
                    // Function name
                    $this->parserStruct['function'] = $token[1];
                } else {
                    // Type hint
                    $this->parserStruct['typehint'] = $token[1];
                }
                break;
            case T_CLASS:
                // Set the class name
                $this->parserStruct['class'] = $token[1];
                // Reset the last token
                $this->lastToken = null;
                break;
            case T_INTERFACE:
                // Set the interface name
                $this->parserStruct['interface'] = $token[1];
                // Reset the last Token
                $this->lastToken = null;
                break;
            default:
                $this->lastToken = null;
        }
    }

    private function startClassOrInterfaceDefinition($token): void
    {
        if ($this->lastToken === null) {
            // New initial interface or class token
            // Store the class or interface definition if there is any in the
            // parser arrays ( There might be more than one class/interface per
            // file )
            $this->storeClassOrInterface();

            // Remember the last token
            $this->lastToken = $token[0];
        } else {
            $this->lastToken = null;
        }
    }

    private function startExtendsOrImplementsDeclaration($token): void
    {
        if ($this->lastToken === null) {
            $this->lastToken = $token[0];
        } else {
            $this->lastToken = null;
        }
    }

    private function t_public($token)
    {
        if ($this->lastToken === null) {
            $this->lastToken = $token[0];
            $this->parserStruct['modifier'] = $token[1];
        } else {
            $this->lastToken = null;
        }
    }

    private function t_protected($token)
    {
        if ($this->lastToken === null) {
            $this->lastToken = $token[0];
            $this->parserStruct['modifier'] = $token[1];
        } else {
            $this->lastToken = null;
        }
    }

    private function t_private($token)
    {
        if ($this->lastToken === null) {
            $this->lastToken = $token[0];
            $this->parserStruct['modifier'] = $token[1];
        } else {
            $this->lastToken = null;
        }
    }

    private function t_doc_comment($token)
    {
        if ($this->lastToken === null) {
            $this->parserStruct['docblock'] = $token[1];
        } else {
            $this->lastToken = null;
            $this->parserStruct['docblock'] = null;
        }
    }

    private function storeClassOrInterface()
    {
        // First we need to check if we should store interface data found so far
        if ($this->parserStruct['interface'] !== null) {
            // Init data storage
            $functions = [];

            // Create the data objects
            foreach ($this->parserStruct['functions'] as $function) {
                // Create the needed parameter objects
                $params = [];
                foreach ($function[2] as $param) {
                    $params[] = new Variable($param[1], $param[0]);
                }
                $functions[] = new Method(
                    $function[0],
                    $function[1],
                    $params
                );
            }
            $interface = new InterfaceDefinition(
                $this->parserStruct['interface'],
                $functions,
                $this->parserStruct['extends']
            );

            // Store in the global interface array
            $this->interfaces[$this->parserStruct['interface']] = $interface;
        } // If there is no interface, we maybe need to store a class
        else if ($this->parserStruct['class'] !== null) {
            // Init data storage
            $functions = [];
            $attributes = [];

            // Create the data objects
            foreach ($this->parserStruct['functions'] as $function) {
                // Create the needed parameter objects
                $params = [];
                foreach ($function[2] as $param) {
                    $params[] = new Variable($param[1], $param[0]);
                }
                $functions[] = new Method(
                    $function[0],
                    $function[1],
                    $params
                );
            }
            foreach ($this->parserStruct['attributes'] as $attribute) {
                $type = null;
                // If there is a docblock try to isolate the attribute type
                if ($attribute[2] !== null) {
                    // Regular expression that extracts types in array annotations
                    $regexp = '/^[\s*]*@var\s+array\(\s*(\w+\s*=>\s*)?(\w+)\s*\).*$/m';
                    if (preg_match($regexp, $attribute[2], $matches)) {
                        $type = $matches[2];
                    } else if ($return = preg_match('/^[\s*]*@var\s+(\S+).*$/m', $attribute[2], $matches)) {
                        $type = trim($matches[1]);
                    }
                }
                $attributes[] = new Attribute(
                    $attribute[0],
                    $attribute[1],
                    $type
                );
            }
            $class = new ClassDefinition(
                $this->parserStruct['class'],
                $attributes,
                $functions,
                $this->parserStruct['implements'],
                $this->parserStruct['extends']
            );

            $this->classes[$this->parserStruct['class']] = $class;
        }

        $this->initParserAttributes();
    }

    private function fixObjectConnections()
    {
        foreach ($this->classes as $class) {
            $implements = [];
            foreach ($class->implements as $key => $impl) {
                $implements[$key] = array_key_exists($impl, $this->interfaces)
                    ? $this->interfaces[$impl]
                    : $this->interfaces[$impl] = new InterfaceDefinition($impl);
            }
            $class->implements = $implements;

            if ($class->extends === null) {
                continue;
            }
            $class->extends = array_key_exists($class->extends, $this->classes)
                ? $this->classes[$class->extends]
                : ($this->classes[$class->extends] = new ClassDefinition($class->extends));
        }
        foreach ($this->interfaces as $interface) {
            if ($interface->extends === null) {
                continue;
            }
            $interface->extends = array_key_exists($interface->extends, $this->interfaces)
                ? $this->interfaces[$interface->extends]
                : ($this->interfaces[$interface->extends] = new InterfaceDefinition($interface->extends));
        }
    }
}
