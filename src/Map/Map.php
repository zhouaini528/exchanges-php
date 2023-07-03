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

    public $order_type='';

    function __construct(string $exchange,string $key,string $secret,string $extra,string $host){
        $this->exchange=$exchange;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }

    function requestAccount(){
        $RequestAccountMap=new RequestAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestAccountMap->setPlatform($this->platform)->setVersion($this->version);
        return $RequestAccountMap;
    }

    function requestMarket(){
        $RequestMarketMap = new RequestMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestMarketMap->setPlatform($this->platform)->setVersion($this->version);
        return $RequestMarketMap;
    }

    function requestTrader(){
        $RequestTraderMap = new RequestTraderMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $RequestTraderMap->setPlatform($this->platform)->setVersion($this->version);

        $this->order_type=$RequestTraderMap->order_type;
        return $RequestTraderMap;
    }

    function responseAccount(){
        $ResponseAccountMap = new ResponseAccountMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $ResponseAccountMap->setPlatform($this->platform)->setVersion($this->version);
        return $ResponseAccountMap;
    }

    function responseMarket(){
        $ResponseMarketMap = new ResponseMarketMap($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
        $ResponseMarketMap->setPlatform($this->platform)->setVersion($this->version);
        return $ResponseMarketMap;
    }

    function responseTrader(){
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


