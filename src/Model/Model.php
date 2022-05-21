<?php

namespace Oaa\RestOrm\Model;

use Oaa\RestOrm\Builder\Builder;

abstract class Model implements ModelInterface
{
    protected $api;
    protected $endpoint;

    protected $fillable = [];

    public $attributes = [];

    public $original = [];

    protected static $filtered = [
        'api',
        'endpoint',
        'fillable',
        'filtered'
    ];

    /**
     * Creates a new Model Instance
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function where($param, $val)
    {
        return new Builder($this);
    }

    public static function find($id)
    {
        $b = new Builder(new static, $id);
        return $b->get();
    }

    public static function all()
    {
        $b = new Builder(new static);
        return $b->get();
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

    public function save()
    {
        $b = new Builder($this);
        //return $b->save();
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
        if(!in_array($key, static::$filtered)) {
            $this->{$key} = $value;
            $this->attributes[$key] = $value;
        }
    }

    public function newInstance($attributes = [])
    {
        return $this->fill($attributes);
    }

    public function isDirty()
    {
        return $this->attributes != $this->original;
    }
}