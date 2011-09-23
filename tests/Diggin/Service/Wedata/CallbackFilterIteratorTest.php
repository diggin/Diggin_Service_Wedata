<?php

namespace DigginTests\Service\Wedata;

use Diggin\Service\Wedata\CallbackFilterIterator;

class CallbackFilterIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCompatible()
    {
        $target = new \ArrayIterator(array('a' => 1, 'b' => 2, 'c' => 2, 'd' => 3));

        $filterd = new CallbackFilterIterator($target, function ($current, $key, $iterator) {
            return (bool) ($current === 2) and ($key === 'c');
        });

        $array = iterator_to_array($filterd);

        $this->assertEquals('c', key($array));
    }
}
