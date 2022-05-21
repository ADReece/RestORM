<?php

namespace Oaa\RestOrm\Builder;

use GuzzleHttp\Client;

abstract class RestOrmBuilder
{
    public Client $client;

    protected $model;

    public $uri;
    public $body;

    public function __construct($uri, $body=null)
    {
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
            //Params
        ]);

        if($res->getStatusCode() != 200){
            return $res->getStatusCode();
        }

        $result = json_decode($res->getBody()->getContents());

        if(property_exists($result, 'data')){
            $result = $result->data;
        }

        return $result;
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