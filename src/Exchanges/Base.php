<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

interface Base
{
    /**
     *  返回原始
     * */
    //function platform();
}

interface BaseTrader
{
    /**
     * 
     * */
    function sell(array $data);
    
    /**
     *
     * */
    function buy(array $data);
    
    /**
     *
     * */
    function cancel(array $data);
    
    /**
     *
     * */
    function update(array $data);
    
    /**
     *
     * */
    function show(array $data);
    
    /**
     *
     * */
    function showAll(array $data);
}

interface BaseMarket{
    /**
     *
     * */
    function depth(array $data);
}

interface BaseAccount{
    /**
     *
     * */
    function position(array $data);
}