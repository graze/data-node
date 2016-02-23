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

namespace Graze\DataNode\Test\Unit\Node;

use Graze\DataNode\NodeCollection;
use Graze\DataNode\NodeInterface;
use Graze\DataNode\Test\TestCase;
use Graze\DataStructure\Collection\Collection;
use InvalidArgumentException;
use Mockery as m;

class NodeCollectionTest extends TestCase
{
    public function testIsCollection()
    {
        static::assertInstanceOf(Collection::class, new NodeCollection());
    }

    public function testCanAddADataNode()
    {
        $collection = new NodeCollection();
        $node = m::mock(NodeInterface::class);
        static::assertSame($collection, $collection->add($node));
    }

    public function testAddingANonDataNodeWillThrowAnException()
    {
        $node = m::mock('Graze\Extensible\ExtensibleInterface');

        $this->expectException(InvalidArgumentException::class);

        $collection = new NodeCollection();
        $collection->add($node);
    }

    public function testCallingApplyWillModifyTheContentsUsingReference()
    {
        $node = m::mock(NodeInterface::class);
        $node->shouldReceive('someMethod')
             ->once()
             ->andReturn(null);

        $collection = new NodeCollection();
        $collection->add($node);

        $collection->apply(function ($item) {
            $item->someMethod();
        });

        $item = $collection->getAll()[0];
        static::assertSame($node, $item);
    }

    public function testCallingApplyWillModifyTheContentsUsingReturnValue()
    {
        $node = m::mock(NodeInterface::class);
        $node->shouldReceive('someMethod')
             ->once()
             ->andReturn(null);

        $collection = new NodeCollection();
        $collection->add($node);

        $collection->apply(function ($item) {
            $item->someMethod();
            return $item;
        });

        $item = $collection->getAll()[0];
        static::assertSame($node, $item);
    }

    public function testFirstWithNoCallbackWillReturnTheFirstEntry()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($first, $collection->first());
    }

    public function testLastWithNoCallbackWillReturnTheFirstEntry()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($second, $collection->last());
    }

    public function testFirstWithCallbackWillReturnTheFirstThatMatches()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);
        $third = m::mock(NodeInterface::class);

        $first->shouldReceive('thisOne')
              ->andReturn(false);
        $second->shouldReceive('thisOne')
               ->andReturn(true);
        $third->shouldReceive('thosOne')
              ->andReturn(true);

        $collection = new NodeCollection([$first, $second, $third]);

        static::assertSame($second, $collection->first(function ($item) {
            return $item->thisOne();
        }));
    }

    public function testLastWithCallbackWillReturnTheFirstThatMatches()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);
        $third = m::mock(NodeInterface::class);

        $first->shouldReceive('thisOne')
              ->andReturn(true);
        $second->shouldReceive('thisOne')
               ->andReturn(true);
        $third->shouldReceive('thisOne')
              ->andReturn(false);

        $collection = new NodeCollection([$first, $second, $third]);

        static::assertSame($second, $collection->last(function ($item) {
            return $item->thisOne();
        }));
    }

    public function testFirstWithCallbackWillReturnDefaultIfNoMatchesAreFound()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);
        $default = m::mock(NodeInterface::class);

        $first->shouldReceive('thisOne')
              ->andReturn(false);
        $second->shouldReceive('thisOne')
               ->andReturn(false);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($default, $collection->first(function ($item) {
            return $item->thisOne();
        }, $default));
        static::assertNull($collection->first(function ($item) {
            return $item->thisOne();
        }));
    }

    public function testLastWithCallbackWillReturnDefaultIfNoMatchesAreFound()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);
        $default = m::mock(NodeInterface::class);

        $first->shouldReceive('thisOne')
              ->andReturn(false);
        $second->shouldReceive('thisOne')
               ->andReturn(false);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($default, $collection->last(function ($item) {
            return $item->thisOne();
        }, $default));
        static::assertNull($collection->last(function ($item) {
            return $item->thisOne();
        }));
    }

    public function testCloneWillCloneTheChildObjects()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);

        $collection = new NodeCollection([$first, $second]);
        $collection2 = $collection->getClone();

        static::assertNotSame($collection, $collection2);
        static::assertEquals($collection->count(), $collection2->count());
        for ($i = 0; $i < $collection->count(); $i++) {
            static::assertNotSame($collection->getAll()[$i], $collection2->getAll()[$i]);
        }
    }

    public function testToString()
    {
        $collection = new NodeCollection();
        static::assertEquals("NodeCollection", "$collection");
    }
}
