<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;
use Lin\Exchange\Config\AccountInterface;
use Lin\Exchange\Config\MarketInterface;
use Lin\Exchange\Config\TraderInterface;

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

class Binance
{
    protected $key;
    protected $secret;
    protected $host;
    
    protected $platform;
    
    function __construct(array $data){
        $this->key=$data['key'] ?? '';
        $this->secret=$data['secret'] ?? '';
        $this->host=$data['host'] ?? '';
        
        $this->platform=new BinanceApi($this->key,$this->secret,$this->host);
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