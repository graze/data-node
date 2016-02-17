<?php

namespace Graze\DataNode\Test\Unit\Node;

use Graze\DataNode\NodeCollection;
use Graze\DataNode\NodeInterface;
use Graze\DataNode\Test\TestCase;
use Graze\DataStructure\Collection\Collection;
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

        static::setExpectedException(
            'InvalidArgumentException',
            "The specified value does not implement NodeInterface"
        );

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

        $collection->apply(function (&$item) {
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

        $first->shouldReceive('thisOne')
              ->andReturn(false);
        $second->shouldReceive('thisOne')
               ->andReturn(true);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($second, $collection->first(function ($item) {
            return $item->thisOne();
        }));
    }

    public function testLastWithCallbackWillReturnTheFirstThatMatches()
    {
        $first = m::mock(NodeInterface::class);
        $second = m::mock(NodeInterface::class);

        $first->shouldReceive('thisOne')
              ->andReturn(true);
        $second->shouldReceive('thisOne')
               ->andReturn(false);

        $collection = new NodeCollection([$first, $second]);

        static::assertSame($first, $collection->last(function ($item) {
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

    public function tesLastWithCallbackWillReturnDefaultIfNoMatchesAreFound()
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
}