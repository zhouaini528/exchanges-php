<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Bitmex\Bitmex as BitmexApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class Base
{
    protected $platform;
    
    function __construct(BitmexApi $platform){
        $this->platform=$platform;
    }
}

class Account extends Base implements AccountInterface
{
    /**
     *
     * */
    function position(array $data){
        return [];
    }
    
    /**
     *
     * */
    function get(array $data){
        return $this->platform->position()->get($data);
    }
}

class Market extends Base implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class Trader extends Base implements TraderInterface
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
        return new Account($this->platform);
    }
    
    function market(){
        return new Market($this->platform);
    }
    
    function trader(){
        return new Trader($this->platform);
    }
    
    function getPlatform(string $type=''){
        return $this->platform;
    }
}