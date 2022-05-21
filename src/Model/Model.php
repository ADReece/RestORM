<?php

namespace Oaa\RestOrm\Model;

use Oaa\RestOrm\Builder\BuilderInstance;

abstract class Model implements ModelInterface
{
    public $api;
    public $endpoint;

    public $attributes = [];

    public $original;

    protected static $traitInitializers = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function where($param, $val)
    {
        $b = new BuilderInstance($this->api.$this->endpoint);
        return $b;
    }

    public function find($id)
    {
        $b = new BuilderInstance($this->api.$this->endpoint."/".$id);
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

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function newInstance($attributes = [])
    {
        $model = new static((array) $attributes);
    }
}