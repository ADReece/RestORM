<?php

namespace Oaa\RestOrm\Model;

use Oaa\RestOrm\Builder\Builder;

abstract class Model implements ModelInterface
{
    public $api;
    public $endpoint;

    public $attributes = [];

    public $original;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function where($param, $val)
    {
        return new Builder($this, $this->api.$this->endpoint);
    }

    public function find($id)
    {
        $b = new Builder($this,$this->api.$this->endpoint."/".$id);
        return $b->get();
    }

    public static function create(array $params)
    {

    }

    public function save()
    {

    }

    public function delete()
    {

    }

    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function setAttribute($key, $value)
    {
        $this->{$key} = $value;
        $this->attributes[$key] = $value;
    }

    public function newInstance($attributes = [])
    {
        return $this->fill($attributes);
    }
}