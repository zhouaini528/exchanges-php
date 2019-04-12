<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

/**
 * 初始化映射对象
 * */
class Map
{
    protected $platform;
    
    function __construct(string $platform){
        $this->platform=$platform;
    }
    
    function request_account(){
        return new RequestAccountMap($this->platform);
    }
    
    function request_market(){
        return new RequestMarketMap($this->platform);
    }
    
    function request_trader(){
        return new RequestTraderMap($this->platform);
    }
    
    function response_account(){
        return new ResponseAccountMap($this->platform);
    }
    
    function response_market(){
        return new ResponseMarketMap($this->platform);
    }
    
    function response_trader(){
        return new ResponseTraderMap($this->platform);
    }
}


