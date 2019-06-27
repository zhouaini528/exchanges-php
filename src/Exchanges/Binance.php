<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBinance
{
    protected $platform;
    
    function __construct(BinanceApi $platform){
        $this->platform=$platform;
    }
}

class AccountBinance extends BaseBinance implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return $this->platform->user()->getAccount($data);
    }
}

class MarketBinance extends BaseBinance implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBinance extends BaseBinance implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        return $this->platform->trade()->postOrder($data);
    }
    
    /**
     *
     * */
    function buy(array $data){
        return $this->platform->trade()->postOrder($data);
    }
    
    /**
     *
     * */
    function cancel(array $data){
        return $this->platform->trade()->deleteOrder($data);
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
        return $this->platform->user()->getOrder($data);
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
        return new AccountBinance($this->platform);
    }
    
    function market(){
        return new MarketBinance($this->platform);
    }
    
    function trader(){
        return new TraderBinance($this->platform);
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