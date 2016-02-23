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

use Graze\DataStructure\Collection\Collection;
use InvalidArgumentException;

/**
 * Class NodeCollection
 *
 * A Collection of DataNodes that can be acted upon by a flow
 *
 * @package Graze\DataFlow\Node
 */
class NodeCollection extends Collection implements NodeCollectionInterface
{
    /**
     * @inheritdoc
     */
    public function add($value)
    {
        if (!($value instanceof NodeInterface)) {
            throw new InvalidArgumentException("The specified value does not implement NodeInterface");
        }
        return parent::add($value);
    }

    /**
     * @inheritdoc
     */
    public function apply(callable $fn)
    {
        foreach ($this->items as &$item) {
            $out = call_user_func($fn, $item);
            if (isset($out) && ($out instanceof NodeInterface)) {
                $item = $out;
            }
        }

        return $this;
    }

    /**
     * On clone, clone all flows too
     */
    public function __clone()
    {
        foreach ($this->items as &$item) {
            $item = clone $item;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "NodeCollection";
    }

    /**
     * @inheritdoc
     */
    public function getClone()
    {
        return clone $this;
    }

    /**
     * @inheritdoc
     */
    public function first(callable $fn = null, $default = null)
    {
        if (is_null($fn)) {
            return count($this->items) > 0 ? reset($this->items) : $default;
        }

        foreach ($this->getIterator() as $value) {
            if (call_user_func($fn, $value)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function last(callable $fn = null, $default = null)
    {
        if (is_null($fn)) {
            return count($this->items) > 0 ? end($this->items) : $default;
        }

        foreach (array_reverse($this->items) as $value) {
            if (call_user_func($fn, $value)) {
                return $value;
            }
        }

        return $default;
    }
}
