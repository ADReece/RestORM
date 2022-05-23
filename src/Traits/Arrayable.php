<?php

namespace Oaa\RestOrm\Traits;

/**
 * Adds properties and methods depended on by the Iterator Interface.
 * Implement Iterator and use this trait to make a class iterable.
 */
trait Arrayable {

    private $pos = 0;

    public function __construct()
    {
        $this->pos = 0;
    }

    public function current()
    {
        return $this->items[$this->pos];
    }

    public function next()
    {
        return ++$this->pos;
    }

    public function key()
    {
        return $this->pos;
    }

    public function valid()
    {
        return isset($this->items[$this->pos]);
    }

    public function rewind()
    {
        $this->pos = 0;
    }

}