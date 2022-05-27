<?php

namespace Oaa\RestOrm\Model;

use Oaa\RestOrm\Builder\Builder;

abstract class Model implements ModelInterface
{
    protected $api;

    protected $endpoint;

    public $original = [];

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

    public function __get($attribute)
    {
        if(key_exists($attribute, $this->attributes)){
            return $this->attributes[$attribute];
        }
    }

    public function __set($attribute, $value)
    {
        if(key_exists($attribute, $this->attributes)){
            $this->attributes[$attribute] = $value;
        }
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
    public static function create(array $params) : Model | bool
    {
        $new = new static($params);
        foreach($params as $param){
            if(!in_array($param, $new->fillable)){
                return false;
                //Change to return a NonFillableException;
            }
        }

        $b = new Builder($new);
        $b->create($params);
        return $new;
    }

    public function save() : Model
    {
        $b = new Builder($this);
        //return $b->save();
    }

    public function delete() : bool
    {
        $b = new Builder($this);
        if($b->delete()){ return true; }
        return false;
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