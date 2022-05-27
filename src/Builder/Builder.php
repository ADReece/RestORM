<?php

namespace Oaa\RestOrm\Builder;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Oaa\RestOrm\Collection\Collection;
use \ReflectionClass;

class Builder
{
    protected Client $client;
    protected $model;
    protected $uri;
    protected $body;

    public function __construct($model, $id=null, $body=null)
    {
        $this->model = $model;
        $reflect = new ReflectionClass($model);
        $this->uri = $model->api.($model->endpoint ?? strtolower($reflect->getShortName())).(!is_null($id) ? "/".$id : null);
        $this->body = $body;

        $this->client = new Client([
            'timeout' => 0,
            'allow_redirects' => false,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function get()
    {
        try{
            $res = $this->client->get($this->uri);

            if($res->getStatusCode() != 200){
                return $res->getStatusCode();
            }
            $result = json_decode($res->getBody()->getContents());
            if(!is_null($result) && $result != []){
                if(property_exists($result, 'data')){
                    $result = $result->data;
                }
                return $this->newModelInstance($result);
            }
            return null;
        } catch (\Exception $e) {
            //Need to create exceptions
            return null;
        }
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

    public function save()
    {
        //If the model exists, update it.  If not then create it.
    }

    public function create($params)
    {
        $res = $this->client->post($this->uri ,[
            RequestOptions::FORM_PARAMS => $params
        ]);

        $result = $res->getBody()->getContents();

        return $this->getModel()->newInstance($result);
    }
    public function delete()
    {
        $res = $this->client->delete($this->uri . '/' . $this->model->id);

        $result = $res->getBody()->getContents();

        return $result;
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