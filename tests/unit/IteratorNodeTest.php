<?php

namespace Graze\DataNode\Test\Unit;

use ArrayIterator;
use Graze\DataNode\IteratorNode;
use Graze\DataNode\IteratorNodeInterface;
use Graze\DataNode\Test\TestCase;
use Iterator;
use Traversable;

class IteratorNodeText extends TestCase
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
        static::assertEquals(['first' => 'value1', 'second' => 'value2'], iterator_to_array($node));
    }

    public function testInstantiateWithIterator()
    {
        $node = new IteratorNode(new ArrayIterator(['first', 'second']);
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
}
