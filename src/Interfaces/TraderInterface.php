<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Interfaces;

/**
 * 交易接口
 * */
interface TraderInterface extends BaseInterface
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