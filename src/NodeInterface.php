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

interface NodeInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * Return a clone of this object
     *
     * @return static
     */
    public function getClone();
}
