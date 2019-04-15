<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class Base
{
    protected $platform;
    
    function __construct(BinanceApi $platform){
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
        return [];
    }
    
    /**
     *
     * */
    function buy(array $data){
        return [];
    }
    
    /**
     *
     * */
    function cancel(array $data){
        return [];
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
        return [];
    }
    
    /**
     *
     * */
    function showAll(array $data){
        return [];
    }
}

class Binance
{
    protected $platform;
    
    function __construct($key,$secret,$host=''){
        $host=empty($host) ? 'https://api.binance.com' : $host ;
        
        $this->platform=new BinanceApi($key,$secret,$host);
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