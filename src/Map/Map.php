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

    protected $exchange;
    protected $key;
    protected $secret;
    protected $extra;
    protected $host;

    protected $platform='';
    protected $version='';

    function __construct(string $exchange,string $key,string $secret,string $extra,string $host){
        $this->exchange=$exchange;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }

    function request_account(){
        return new RequestAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    function request_market(){
        return new RequestMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    function request_trader(){
        return new RequestTraderMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    function response_account(){
        return new ResponseAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    function response_market(){
        return new ResponseMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    function response_trader(){
        return new ResponseTraderMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }

    public function setPlatform(string $platform=''){
        $this->platform=$platform;
        return $this;
    }

    public function setVersion(string $version=''){
        $this->version=$version;
        return $this;
    }
}


