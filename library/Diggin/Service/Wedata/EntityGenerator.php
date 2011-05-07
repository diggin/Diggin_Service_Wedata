<?php

namespace Diggin\Service\Wedata;

use Diggin\Service\Wedata\ServiceClient,
    Zend\Filter\FilterChain,
    Zend\Filter\Word\SeparatorToCamelCase;

/**
 *  Item Data Entity Generator
 */
class EntityGenerator
{
    private $filterChain;

    /**
     * @param string Output Directory path
     * @param array A set of Database instances.
     * @return array Generated models set. array('class name' => path,)
     */
    public static function generateAll($outputDirectory, $databases = null)
    {
        $entityGenerator = new self;

        if (!$databases) {
            $client = new ServiceClient;
            $databases = $client->getDatabases();
        }

        $generatedMaps = array();
        foreach ($databases as $database) {
            $generatedMaps[$database->getName()] = $entityGenerator->generate($outputDirectory, $database);
        }

        return $generatedMaps;
    }

    public function generate($outputDirectory, Database $database)
    {
        $className = $this->filterName($database->getName());
        $file = $className.'.php';

        return $outputDirectory.DIRECTORY_SEPARATOR.$file;
    }

    protected function filterName($name)
    {
        if (!$this->filterChain) {
            $filterChain = new FilterChain;
            $filterChain->attach(new SeparatorToCamelCase('_'));
            $filterChain->attach(new SeparatorToCamelCase(' '));
            $filterChain->attach(new SeparatorToCamelCase('-'));
            $filterChain->attach(function ($var){return rawurlencode($var);});
            $filterChain->attach(function ($var){return str_replace('%', '',$var);});
            $this->filterChain = $filterChain;
        }

        return $this->filterChain->filter($name);
    }
}
