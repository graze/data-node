<?php

namespace Graze\DataNode;

use Graze\DataStructure\Collection\CollectionInterface;

interface NodeCollectionInterface extends CollectionInterface, NodeInterface
{
    /**
     * @param callable $fn
     *
     * @return NodeCollectionInterface
     */
    public function apply(callable $fn);

    /**
     * @param callable|null      $fn
     * @param NodeInterface|null $default
     *
     * @return NodeInterface|null
     */
    public function first(callable $fn = null, $default = null);

    /**
     * @param callable|null      $fn
     * @param NodeInterface|null $default
     *
     * @return NodeInterface|null
     */
    public function last(callable $fn = null, $default = null);
}
