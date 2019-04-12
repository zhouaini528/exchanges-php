<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Okex\OkexFuture;
use Lin\Okex\OkexSpot;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

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
        //判断是期货还是现货
        if(isset($data['match_price'])){
            //return $this->platform_future->order()->post($data);
        }else{
            //return $this->platform_spot->order()->post($data);
        }
        return [];
    }
    
    /**
     *
     * */
    function buy(array $data){
        print_r($data);
        echo 'okex buy';
        return [];
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
    protected $platform_future;
    protected $platform_spot;
    
    
    function __construct($key,$secret,$passphrase,$host){
        $this->platform_future=new OkexFuture($key,$secret,$passphrase,$host);
        
        $this->platform_spot=new OkexSpot($key,$secret,$passphrase,$host);
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