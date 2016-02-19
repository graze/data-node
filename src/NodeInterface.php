<?php

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
