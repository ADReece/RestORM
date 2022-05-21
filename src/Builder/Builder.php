<?php

namespace Oaa\RestOrm\Builder;

use GuzzleHttp\Client;
use Oaa\RestOrm\Collection\Collection;

class Builder
{
    protected Client $client;
    protected $model;
    protected $uri;
    protected $body;

    public function __construct($model, $id=null, $body=null)
    {
        $this->model = $model;
        $this->uri = $model->api.($model->endpoint ?? strtolower($model::class)).(!is_null($id) ? "/".$id : null);
        $this->body = $body;

        $this->client = new Client([
            'timeout' => 0,
            'allow_redirects' => false
        ]);
    }

    public function get()
    {
        try{
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
        } catch (\Exception $e) {
            //Need to create exceptions
            return null;
        }
    }

    public function save(){
        //If the model exists, update it.  If not then create it.
    }

    public function all()
    {
        try{
            $res = $this->client->get($this->uri, [
                //
            ]);

            $result = json_decode($res->getBody()->getContents());

            //Get the data portion of the response
            if(is_object($result) && property_exists($result, 'data')){
                $result = $result->data;
                //An extra check for a data prop (Returned in paginated responses)
                if(is_object($result) && property_exists($result, 'data')){
                    $result = $result->data;
                }
            }

            if(gettype($result) == 'array'){
                $data = [];
                foreach($result as $r){
                    $data[] = $this->newModelInstance($r);
                }
                return new Collection($data);
            }
            return $this->newModelInstance($result);

        } catch (\Exception $e) {
            //Need to create exceptions
            return null;
        }
    }

    public function newModelInstance($attributes = [])
    {
        return $this->getModel()->newInstance($attributes);
    }

    public function getModel()
    {
        return $this->model;
    }
}