<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Exchanges\BaseTrader;

class Trader extends Base implements BaseTrader
{
    /**
     *
     * */
    function sell(array $data){
        $this->platform->trader()->sell($data);
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