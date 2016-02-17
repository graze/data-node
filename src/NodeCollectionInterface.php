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
    public function apply($fn);

    /**
     * @param callable      $fn
     * @param NodeInterface $default
     *
     * @return NodeInterface|null
     */
    public function first(callable $fn = null, $default = null);

    /**
     * @param callable      $fn
     * @param NodeInterface $default
     *
     * @return NodeInterface|null
     */
    public function last(callable $fn = null, $default = null);
}
