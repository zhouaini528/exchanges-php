<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Okex\OkexFuture;
use Lin\Okex\OkexSpot;
use Lin\Exchange\Config\AccountInterface;
use Lin\Exchange\Config\MarketInterface;
use Lin\Exchange\Config\TraderInterface;

class Base
{
    protected $platform_future;
    protected $platform_spot;
    
    function __construct(OkexFuture $platform_future,OkexSpot $platform_spot){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
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

class Okex
{
    protected $key;
    protected $secret;
    protected $passphrase;
    protected $host;
    
    protected $platform_future;
    protected $platform_spot;
    
    
    function __construct(array $data){
        $this->key=$data['key'] ?? '';
        $this->secret=$data['secret'] ?? '';
        $this->passphrase=$data['passphrase'] ?? '';
        $this->host=$data['host'] ?? '';

        $this->platform_future=new OkexFuture($this->key,$this->secret,$this->passphrase,$this->host);
        
        $this->platform_spot=new OkexSpot($this->key,$this->secret,$this->passphrase,$this->host);
    }
    
    function account(){
        return new Account($this->platform_future,$this->platform_spot);
    }
    
    function market(){
        return new Market($this->platform_future,$this->platform_spot);
    }
    
    function trader(){
        return new Trader($this->platform_future,$this->platform_spot);
    }
}