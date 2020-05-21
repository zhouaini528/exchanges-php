<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Coinbase\Coinbase as CoinbaseApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseCoinbase
{
    protected $platform;
    
    function __construct(CoinbaseApi $platform){
        $this->platform=$platform;
    }
}

class AccountCoinbase extends BaseCoinbase implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketCoinbase extends BaseCoinbase implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderCoinbase extends BaseCoinbase implements TraderInterface
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

class Coinbase
{
    protected $platform;
    
    function __construct($key,$secret,$passphrase,$host=''){
        $host=empty($host) ? 'https://api.pro.coinbase.com' : $host ;
        
        $this->platform=new CoinbaseApi($key,$secret,$passphrase,$host);
    }
    
    function account(){
        return new AccountCoinbase($this->platform);
    }
    
    function market(){
        return new MarketCoinbase($this->platform);
    }
    
    function trader(){
        return new TraderCoinbase($this->platform);
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