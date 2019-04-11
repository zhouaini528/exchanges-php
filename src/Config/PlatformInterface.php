<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Config;


interface TraderInterface
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

interface MarketInterface
{
    /**
     *
     * */
    function depth(array $data);
}

interface AccountInterface
{
    /**
     *
     * */
    function position(array $data);
}