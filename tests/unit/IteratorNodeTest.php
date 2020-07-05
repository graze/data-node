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

namespace Graze\DataNode\Test\Unit;

use ArrayIterator;
use Graze\DataNode\IteratorNode;
use Graze\DataNode\IteratorNodeInterface;
use Graze\DataNode\Test\AbstractTestCase;
use Iterator;
use Traversable;

class IteratorNodeTest extends AbstractTestCase
{
    public function testImplements()
    {
        $node = new IteratorNode([]);
        static::assertInstanceOf(Iterator::class, $node);
        static::assertInstanceOf(IteratorNodeInterface::class, $node);
    }

    public function testInstantiateWithArray()
    {
        $node = new IteratorNode(['first', 'second']);
        static::assertEquals(['first', 'second'], iterator_to_array($node));
    }

    public function testInstantiateWithArrayAndKeys()
    {
        $node = new IteratorNode(['first' => 'value1', 'second' => 'value2']);
        static::assertEquals(['first' => 'value1', 'second' => 'value2'], iterator_to_array($node, true));
    }

    public function testInstantiateWithIterator()
    {
        $node = new IteratorNode(new ArrayIterator(['first', 'second']));
        static::assertEquals(['first', 'second'], iterator_to_array($node));
    }

    public function testFetchWillReturnAnIterator()
    {
        $node = new IteratorNode(['first', 'second']);
        $iterator = $node->fetch();
        static::assertInstanceOf(Iterator::class, $iterator);
        static::assertInstanceOf(Traversable::class, $iterator);
        static::assertEquals(['first', 'second'], iterator_to_array($iterator));
    }

    public function testFetchWillFilterOnCallable()
    {
        $node = new IteratorNode(['first', 'second']);
        $iterator = $node->fetch(function ($value) {
            return $value == 'first';
        });
        static::assertEquals(['first'], iterator_to_array($iterator));
    }

    public function testClone()
    {
        $iterator = new ArrayIterator(['first', 'second']);
        $node = new IteratorNode($iterator);
        $clone = $node->getClone();

        static::assertNotSame($clone, $node);

        $iterator->append('third');

        static::assertEquals(['first', 'second', 'third'], iterator_to_array($node));
        static::assertEquals(['first', 'second'], iterator_to_array($clone));
    }

    public function testToString()
    {
        $node = new IteratorNode([]);
        static::assertEquals(IteratorNode::class, $node->__toString());
    }
}
