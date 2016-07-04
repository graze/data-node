<?php

namespace Graze\DataNode;

use ArrayIterator;
use CallbackFilterIterator;
use Iterator;
use IteratorIterator;
use Traversable;

class IteratorNode extends IteratorIterator implements IteratorNodeInterface
{
    /**
     * IteratorNode constructor.
     *
     * @param array|Traversable $source
     */
    public function __construct($source)
    {
        $iterator = is_array($source) ? new ArrayIterator($source) : $source;
        parent::__construct($iterator);
    }

    /**
     * @param callable|null $filter
     *
     * @return Iterator
     */
    public function fetch(callable $filter = null)
    {
        if ($filter) {
            return new CallbackFilterIterator($this, $filter);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__;
    }

    /**
     * Return a clone of this object
     *
     * @return static
     */
    public function getClone()
    {
        return clone $this;
    }
}
