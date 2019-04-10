<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;

class Base
{
    protected $platform;
    
    function __construct(string $key='',string $secret='',string $host='https://api.binance.com'){
        $this->platform=new BinanceApi($key,$secret,$host);
    }
}

class Account extends Base implements BaseAccount
{
    /**
     *
     * */
    function position(array $data){
        
    }
}

class Market  extends Base  implements BaseMarket
{
    /**
     *
     * */
    function depth(array $data){
        
    }
}

class Trader  extends Base  implements BaseTrader
{
    /**
     *
     * */
    function sell(array $data){
        $this->platform->trade()->postOrder($data);
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
    
    function __construct(array $data){
        $this->key=$data['key'] ?? '';
        $this->secret=$data['secret'] ?? '';
        $this->host=$data['host'] ?? '';
    }
    
    function account(){
        return new Account($this->key,$this->secret,$this->host);
    }
    
    function market(){
        return new Market($this->key,$this->secret,$this->host);
    }
    
    function trader(){
        return new Trader($this->key,$this->secret,$this->host);
    }
}