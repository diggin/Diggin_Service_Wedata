<?php
namespace Diggin\Service\Wedata;

use FilterIterator,
    Iterator;

/**
 * 
 */
class CallbackFilterIterator extends FilterIterator
{
    private $callback;

    public function __construct(Iterator $iterator, $callback)
    {
        if (!is_callable($callback)) {
            throw new Exception\InvalidArgumentException('provided callback is not callback');
        }

        $this->callback = $callback;

        return parent::__construct($iterator);
    }

    public function accept()
    {
        $iterator = $this->getInnerIterator();
        return call_user_func($this->callback, $iterator->current(), $iterator->key(), $iterator);
    }
}
