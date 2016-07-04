<?php
/**
 * This file is part of graze/data-node
 *
 * Copyright (c) 2016 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/data-node/blob/master/LICENSE.md
 * @link    https://github.com/graze/data-node
 */

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
     * @note This will traverse the current iterator source to produce a clone, potentially moving past the point
     *       required
     *
     * @return static
     */
    public function getClone()
    {
        return new IteratorNode(iterator_to_array($this));
    }
}
