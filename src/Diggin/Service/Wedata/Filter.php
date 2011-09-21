<?php

namespace Diggin\Service\Wedata;

use Diggin\Service\Wedata\Client\ServiceClient,
    Zend\Filter\FilterChain,
    Zend\Filter\Word\SeparatorToCamelCase;

/**
 * A utiliy for Generator
 */ 
class Filter
{
    /**
     * @var Zend\Filter\FilterChain
     */
    private static $databaseNameFilter;

    private function __construct(){}

    public static function filterDatabaseName($databaseName)
    {
        if (!self::$databaseNameFilter instanceof FilterChain) {
            $filterChain = new FilterChain;
            $filterChain->attach(new SeparatorToCamelCase('_'));
            $filterChain->attach(new SeparatorToCamelCase(' '));
            $filterChain->attach(new SeparatorToCamelCase('-'));
            $filterChain->attach(function ($var){return rawurlencode($var);});
            $filterChain->attach(function ($var){return str_replace('%', '',$var);});
            self::$databaseNameFilter = $filterChain;
        }

        return self::$databaseNameFilter->filter($databaseName);
    }
}
