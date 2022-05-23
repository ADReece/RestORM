<?php

namespace Oaa\RestOrm\Model;

use Oaa\RestOrm\Builder\Builder;

abstract class Model implements ModelInterface
{
    protected $api;

    protected $endpoint;

    protected $original = [];

    protected $fillable = [];

    protected $hidden = [];

    protected $changes = [];

    public $attributes = [];

    /**
     * Creates a new Model Instance
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function find($id)
    {
        $b = new Builder(new static, $id);
        return $b->get();
    }

    public static function all()
    {
        $b = new Builder(new static);
        return $b->all();
    }

    /**
     * Creates a new model with the given params.
     * @param array $params
     * @return Model
     */
    public static function create(array $params) : Model
    {
        return $new = new static($params);
        return $new->save();
    }

    public function save() : Model
    {
        $b = new Builder($this);
        //return $b->save();
    }

    public function delete() : bool
    {

    }

    public function fill($attributes) : Model
    {
        foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function setAttribute($key, $value) : void
    {
        if(!in_array($key, $this->hidden)){
            $this->{$key} = $value;
            $this->attributes[$key] = $value;
            $this->original[$key] = $value;
        }
    }

    public function newInstance($attributes = []) : Model
    {
        return (new static)->fill($attributes);
    }

    public function isDirty() : bool
    {
        return $this->attributes !== $this->original;
    }
}