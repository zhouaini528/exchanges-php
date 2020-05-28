<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Bittrex\Bittrex as BittrexApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBittrex
{
    protected $platform;
    
    function __construct(BittrexApi $platform){
        $this->platform=$platform;
    }
}

class AccountBittrex extends BaseBittrex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketBittrex extends BaseBittrex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBittrex extends BaseBittrex implements TraderInterface
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

class Bittrex
{
    protected $platform;
    
    function __construct($key,$secret,$subaccount_id='',$host=''){
        $host=empty($host) ? 'https://api.bittrex.com' : $host ;
        
        if(stripos($subaccount_id,'http')===0) {
            $host=$subaccount_id;
            $subaccount_id='';
        }
        
        $this->platform=new BittrexApi($key,$secret,$host);
    }
    
    function account(){
        return new AccountBittrex($this->platform);
    }
    
    function market(){
        return new MarketBittrex($this->platform);
    }
    
    function trader(){
        return new TraderBittrex($this->platform);
    }
    
    function getPlatform(string $type=''){
        return $this->platform;
    }
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform->setOptions($options);
    }
}