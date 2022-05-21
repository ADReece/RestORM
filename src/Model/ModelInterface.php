<?php

namespace Oaa\RestOrm\Model;

interface ModelInterface
{
    public static function find($id);

    public static function create(array $params);

    public function save();

    public function delete();
}