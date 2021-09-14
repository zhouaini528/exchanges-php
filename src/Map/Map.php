<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

/**
 * Initialize mapping object
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
        $RequestAccountMap=new RequestAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestAccountMap->setPlatform($this->platform)->setVersion($this->version);
        return $RequestAccountMap;
    }

    function request_market(){
        $RequestMarketMap = new RequestMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestMarketMap->setPlatform($this->platform)->setVersion($this->version);
        return $RequestMarketMap;
    }

    function request_trader(){
        $RequestTraderMap = new RequestTraderMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestTraderMap->setPlatform($this->platform)->setVersion($this->version);
        return $RequestTraderMap;
    }

    function response_account(){
        $ResponseAccountMap = new ResponseAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $ResponseAccountMap->setPlatform($this->platform)->setVersion($this->version);
        return $ResponseAccountMap;
    }

    function response_market(){
        $ResponseMarketMap = new ResponseMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $ResponseMarketMap->setPlatform($this->platform)->setVersion($this->version);
        return $ResponseMarketMap;
    }

    function response_trader(){
        $ResponseTraderMap = new ResponseTraderMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $ResponseTraderMap->setPlatform($this->platform)->setVersion($this->version);
        return $ResponseTraderMap;
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


