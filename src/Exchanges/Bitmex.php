<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Bitmex\Bitmex as BitmexApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBitmex
{
    protected $platform_future=null;

    protected $platform='';
    protected $version='';
    protected $options='';

    protected $key;
    protected $secret;
    protected $host='';

    function __construct($key,$secret,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=$host;
    }

    /**
    Set exchange transaction category, default "spot" transaction. Other options "spot" "margin" "future" "swap"
     */
    public function setPlatform(string $platform=''){
        $this->platform=$platform;
        return $this;
    }

    /**
    Set exchange API interface version. for example "v1" "v3" "v5"
     */
    public function setVersion(string $version=''){
        $this->version=$version;
        return $this;
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->options=$options;
        return $this;
    }

    /***
     *Initialize exchange
     */
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'future':{
                if($this->platform_future == null) $this->platform_future=new BitmexApi($this->key,$this->secret,empty($this->host) ? 'https://www.bitmex.com' : $this->host);
                $this->platform_future->setOptions($this->options);
                return $this->platform_future;
            }
        }

        return null;
    }
}

class AccountBitmex extends BaseBitmex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        $this->platform_future=$this->getPlatform('future');
        return $this->platform_future->position()->get($data);
    }
}

class MarketBitmex extends BaseBitmex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBitmex extends BaseBitmex implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        $this->platform_future=$this->getPlatform('future');
        return $this->platform_future->order()->post($data);
    }

    /**
     *
     * */
    function buy(array $data){
        $this->platform_future=$this->getPlatform('future');
        return $this->platform_future->order()->post($data);
    }

    /**
     *
     * */
    function cancel(array $data){
        $this->platform_future=$this->getPlatform('future');
        return current($this->platform_future->order()->delete($data));
    }

    /**
     *
     * */
    function update(array $data){
        return [];
    }

    /**
     *
     * */
    function show(array $data){
        $this->platform_future=$this->getPlatform('future');
        return $this->platform_future->order()->getOne($data);
    }

    /**
     *
     * */
    function showAll(array $data){
        return [];
    }
}

class Bitmex
{
    protected $platform='';
    protected $version='';
    protected $options=[];

    protected $key;
    protected $secret;
    protected $host='';

    protected $exchange=null;

    function __construct($key,$secret,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=$host;
    }

    function account(){
        $this->exchange= new AccountBitmex($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange= new MarketBitmex($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange= new TraderBitmex($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function getPlatform(string $type=''){
        if($this->exchange==null) $this->exchange=$this->trader();
        return $this->exchange->getPlatform($type);
    }

    /**
    Set exchange transaction category, default "spot" transaction. Other options "spot" "margin" "future" "swap"
     */
    public function setPlatform(string $platform=''){
        $this->platform=$platform;
        return $this;
    }

    /**
    Set exchange API interface version. for example "v1" "v3" "v5"
     */
    public function setVersion(string $version=''){
        $this->version=$version;
        return $this;
    }


    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->options=$options;
        return $this;
    }
}
