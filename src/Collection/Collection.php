<?php

namespace Oaa\RestOrm\Collection;

use Oaa\RestOrm\Traits\Arrayable;

class Collection implements \Iterator
{
    use Arrayable;

    /**
     * @var array|mixed
     */
    protected $items = [];

    /**
     * @param $items
     */
    public function __construct($items=[])
    {
        $this->items = $items;
    }
}