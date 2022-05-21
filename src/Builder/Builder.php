<?php

namespace Oaa\RestOrm\Builder;

use GuzzleHttp\Client;

class Builder
{
    protected Client $client;
    protected $model;
    protected $uri;
    protected $body;

    public function __construct($model, $uri, $body=null)
    {
        $this->model = $model;
        $this->uri = $uri;
        $this->body = $body;

        $this->client = new Client([
            'timeout' => 0,
            'allow_redirects' => false
        ]);
    }

    public function get()
    {
        $res = $this->client->get($this->uri, [
            //
        ]);

        if($res->getStatusCode() != 200){
            return $res->getStatusCode();
        }

        $result = json_decode($res->getBody()->getContents());

        if(property_exists($result, 'data')){
            $result = $result->data;
        }
        return $this->newModelInstance($result);
    }

    public function newModelInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    public function getModel()
    {
        return $this->model;
    }
}