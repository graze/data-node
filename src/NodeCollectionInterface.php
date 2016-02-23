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
