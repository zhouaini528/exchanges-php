<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

//构思  BaseAccount    BaseMarket

interface BaseOrder
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
    
    //必须返回原是对象
}

interface BaseMarket{
    
}

interface BaseAccount{
    
}