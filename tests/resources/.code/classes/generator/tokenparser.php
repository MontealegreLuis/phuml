<?php

class plStructureTokenparserGenerator extends plStructureGenerator
{
    private $interfaces;
    private $parserStruct;
    private $lastToken;

    public function __construct(private $classes)
    {
    }

    private function initGlobalAttributes()
    {
    }

    private function initParserAttributes()
    {
    }

    public function createStructure( array $files )
    {
    }

    private function comma()
    {
    }

    private function opening_bracket()
    {
    }

    private function closing_bracket()
    {
    }

    private function equal_sign()
    {
    }

    private function t_whitespace( $token )
    {
    }

    private function t_function( $token )
    {
    }

    private function t_var( $token )
    {
    }

    private function t_variable( $token )
    {
    }

    private function t_array( $token )
    {
    }

    private function t_constant_encapsed_string( $token )
    {
    }

    private function t_lnumber( $token )
    {
    }

    private function t_dnumber( $token )
    {
    }

    private function t_paamayim_neukudotayim( $token )
    {
    }

    private function t_string( $token )
    {
    }

    private function t_interface( $token )
    {
    }

    private function t_class( $token )
    {
    }

    private function t_implements( $token )
    {
    }

    private function t_extends( $token )
    {
    }

    private function t_public( $token )
    {
    }

    private function t_protected( $token )
    {
    }

    private function t_private( $token )
    {
    }

    private function t_doc_comment( $token )
    {
    }

    private function storeClassOrInterface()
    {
    }

    private function fixObjectConnections()
    {
    }
}
