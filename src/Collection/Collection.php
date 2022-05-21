<?php

namespace Oaa\RestOrm\Collection;

class Collection
{
    public $items = [];

    public function __construct($items=[])
    {
        $this->items = $items;
    }
}