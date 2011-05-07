<?php

namespace Diggin\Service\Wedata;

use Diggin\Service\Wedata\Wedata,
    Diggin\Service\Wedata\ServiceClient,
    Zend\CodeGenerator\Php as PhpCodeGenerator;

/**
 *  Item Data Entity Generator
 */
class EntityGenerator
{
    private $filterChain;

    public static $itemdata_namespace = 'Diggin\\Service\\Wedata\\ItemData';
    public static $ignore_database = 
        array('AustralianConservative', 'blog sina');

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
            if (in_array($database->getName(), static::$ignore_database)) {
                continue;
            }
            $generatedMaps[$database->getName()] = $entityGenerator->generate($outputDirectory, $database);
        }

        return $generatedMaps;
    }

    public function generate($outputDirectory, Database $database)
    {
        $className = Wedata::filterDatabaseName($database->getName());
        $file = $className.'.php';
        $path = $outputDirectory.DIRECTORY_SEPARATOR.$file;

        $codegenFile = new PhpCodeGenerator\PhpFile;
        $codegenFile->setFileName($path);
        $codegenFile->setNamespace(static::$itemdata_namespace);
        
        $codegenClass = new PhpCodeGenerator\PhpClass;
        $codegenClass->setName($className);
        $tableName = strtolower($className);
        $codegenClass->setDocblock(<<<DOCBLOCK
@Entity @Table(name="wedata_$tableName")
DOCBLOCK
);

        $requiredKeys = $database->getRequiredKeysAsArray();
        $optionalKeys = $database->getOptionalKeysAsArray();

        if (in_array('item_id', $requiredKeys + $optionalKeys)) {
            throw new Exception('Database has item_id as key');
        }

        $codegenClass->setProperty(array(
            'name' => 'item_id',
            'visibility' => 'private',
            'docblock' => '@Id @Column(type="integer")'
        ));

        //set Required Keys
        static::addProperties($requiredKeys, $codegenClass);
        static::addProperties($optionalKeys, $codegenClass);

        $codegenFile->setClass($codegenClass);
        $codegenFile->write();

        return $path;
    }

    protected static function addProperties($keys, PhpCodeGenerator\PhpClass $codegenClass)
    {
        foreach ($keys as $key) {
            $codegenClass->setProperty(array(
                'name' => $key,
                'visibility' => 'private',
                'docblock' => '@Column(type="string", length=200)'
            ));
        }
    }
}
