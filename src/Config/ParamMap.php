<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Config;

use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class Base
{
    protected $platform;
    
    function __construct(string $platform){
        $this->platform=$platform;
    }
}

/**
 * 账户接口参数映射
 * */
class ParamMapAccount extends Base implements AccountInterface
{
    /**
     *
     * */
    function position(array $data){
        return $data;
    }
}

/**
 * 行情接口参数映射
 * */
class ParamMapMarket extends Base implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        
    }
}

/**
 * 交易接口参数映射
 * */
class ParamMapTrader extends Base implements TraderInterface
{
    /**
     *  
     * */
    function sell(array $data){
        switch ($this->platform){
            case 'huobi':{
                
                break;
            }
            case 'bitmex':{
                
                break;
            }
            case 'okex':{
                
                break;
            }
            case 'binance':{
                
                break;
            }
        }
        return $data;
    }
    
    /**
     *
     * */
    function buy(array $data){
        return $data;
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

/**
 * 初始化映射对象
 * */
class ParamMap
{
    protected $platform;
    
    function __construct(string $platform){
        $this->platform=$platform;
    }
    
    function account(){
        return new ParamMapAccount($this->platform);
    }
    
    function market(){
        return new ParamMapMarket($this->platform);
    }
    
    function trader(){
        return new ParamMapTrader($this->platform);
    }
}


