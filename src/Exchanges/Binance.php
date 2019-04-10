<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

class Base
{
    function __construct(string $platform,array $data){
        
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
    function __construct(){
        
    }
    
    function account(){
        return new Account();
    }
    
    function market(){
        return new Market();
    }
    
    function trader(){
        return new Trader();
    }
}