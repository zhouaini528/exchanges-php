<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Bitmex\Bitmex as BitmexApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBitmex
{
    protected $platform;
    
    function __construct(BitmexApi $platform){
        $this->platform=$platform;
    }
}

class AccountBitmex extends BaseBitmex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return $this->platform->position()->get($data);
    }
}

class MarketBitmex extends BaseBitmex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBitmex extends BaseBitmex implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        return $this->platform->order()->post($data);
    }
    
    /**
     *
     * */
    function buy(array $data){
        return $this->platform->order()->post($data);
    }
    
    /**
     *
     * */
    function cancel(array $data){
        return current($this->platform->order()->delete($data));
    }
    
    /**
     *
     * */
    function update(array $data){
        return [];
    }
    
    /**
     *
     * */
    function show(array $data){
        return $this->platform->order()->getOne($data);
    }
    
    /**
     *
     * */
    function showAll(array $data){
        return [];
    }
}

class Bitmex
{
    protected $platform;
    
    function __construct($key,$secret,$host=''){
        $host=empty($host) ? 'https://www.bitmex.com' : $host ;
        
        $this->platform=new BitmexApi($key,$secret,$host);
    }
    
    function account(){
        return new AccountBitmex($this->platform);
    }
    
    function market(){
        return new MarketBitmex($this->platform);
    }
    
    function trader(){
        return new TraderBitmex($this->platform);
    }
    
    function getPlatform(string $type=''){
        return $this->platform;
    }
    
    /**
     * Local development sets the proxy
     * @param bool|array
     * $proxy=false Default
     * $proxy=true  Local proxy http://127.0.0.1:12333
     *
     * Manual proxy
     * $proxy=[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     * ]
     *
     * @param mixed
     * */
    function setProxy($proxy=true){
        $this->platform->setProxy($proxy);
    }
}