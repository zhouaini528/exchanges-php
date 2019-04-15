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
    protected $key;
    protected $secret;
    protected $extra;
    protected $host;
    
    function __construct(string $platform,string $key,string $secret,string $extra,string $host){
        $this->platform=$platform;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }
    
    function request_account(){
        return new RequestAccountMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function request_market(){
        return new RequestMarketMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function request_trader(){
        return new RequestTraderMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function response_account(){
        return new ResponseAccountMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function response_market(){
        return new ResponseMarketMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function response_trader(){
        return new ResponseTraderMap($this->platform,$this->key,$this->secret,$this->extra,$this->host);
    }
}


