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
        
    }
}

class Market extends Base implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        
    }
}

class Trader extends Base implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        
    }
    
    /**
     *
     * */
    function buy(array $data){
        
    }
    
    /**
     *
     * */
    function cancel(array $data){
        
    }
    
    /**
     *
     * */
    function update(array $data){
        
    }
    
    /**
     *
     * */
    function show(array $data){
        
    }
    
    /**
     *
     * */
    function showAll(array $data){
        
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
}