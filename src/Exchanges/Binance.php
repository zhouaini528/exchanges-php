<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;
use Lin\Binance\BinanceFuture;

use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBinance
{
    protected $platform_future;
    protected $platform_spot;
    protected $host;
    
    function __construct(BinanceApi $platform_spot,BinanceFuture $platform_future,string $host){
        $this->platform_spot=$platform_spot;
        $this->platform_future=$platform_future;
        $this->host=$host;
    }
    
    protected function checkType(){
        if(stristr($this->host,"fapi")!==false) return 'future';
        return 'spot';
    }
}

class AccountBinance extends BaseBinance implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType()){
            case 'future':{
                return $this->platform_future->user()->getAccount($data);
            }
            case 'spot':{
                return $this->platform_spot->user()->getAccount($data);
            }
        }
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
        switch ($this->checkType()){
            case 'future':{
                return $this->platform_future->trade()->postOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->trade()->postOrder($data);
            }
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType()){
            case 'future':{
                return $this->platform_future->trade()->postOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->trade()->postOrder($data);
            }
        }
    }
    
    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType()){
            case 'future':{
                return $this->platform_future->trade()->deleteOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->trade()->deleteOrder($data);
            }
        }
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
        switch ($this->checkType()){
            case 'future':{
                return $this->platform_future->trade()->getOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->user()->getOrder($data);
            }
        }
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
    protected $platform_future;
    protected $platform_spot;
    protected $host;
    
    function __construct($key,$secret,$host=''){
        $this->host=empty($host) ? 'https://api.binance.com' : $host ;
        
        $this->platform_spot=new BinanceApi($key,$secret,$this->host);
        $this->platform_future=new BinanceFuture($key,$secret,$this->host);
    }
    
    function account(){
        return new AccountBinance($this->platform_spot,$this->platform_future,$this->host);
    }
    
    function market(){
        return new MarketBinance($this->platform_spot,$this->platform_future,$this->host);
    }
    
    function trader(){
        return new TraderBinance($this->platform_spot,$this->platform_future,$this->host);
    }
    
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'spot':{
                return $this->platform_spot;
            }
            case 'future':{
                return $this->platform_future;
            }
            default:{
                return null;
            }
        }
    }
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform_spot->setOptions($options);
        $this->platform_future->setOptions($options);
    }
}