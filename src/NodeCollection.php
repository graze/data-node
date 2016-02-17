<?php

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
     * {@inheritdoc}
     */
    public function add($value)
    {
        if (!($value instanceof NodeInterface)) {
            throw new InvalidArgumentException("The specified value does not implement NodeInterface");
        }
        return parent::add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($fn)
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
     * Return a clone of this object
     *
     * @return NodeInterface
     */
    public function getClone()
    {
        return clone $this;
    }

    /**
     * @param callable   $fn
     * @param mixed|null $default
     *
     * @return NodeInterface|null
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
     * @param callable   $fn
     * @param mixed|null $default
     *
     * @return NodeInterface|null
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
