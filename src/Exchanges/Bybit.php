<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Bybit\BybitV5;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBybit
{
    protected $platform='';
    protected $version='';
    protected $options='';

    protected $key;
    protected $secret;
    protected $host;

    protected $exchange=null;

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
        $this->exchange=new BybitV5($this->key,$this->secret,$this->host);
        $this->exchange->setOptions($this->options);
        return $this->exchange;
    }
}

class AccountBybit extends BaseBybit implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){}
}

class MarketBybit extends BaseBybit implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
    }
}

class TraderBybit extends BaseBybit implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        return $this->getPlatform()->order()->postCreate($data);
    }

    /**
     *
     * */
    function buy(array $data){
        return $this->getPlatform()->order()->postCreate($data);
    }

    /**
     *
     * */
    function cancel(array $data){
        return $this->getPlatform()->order()->postCancel($data);
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
        return $this->getPlatform()->order()->getRealTime($data);
    }

    /**
     *
     * */
    function showAll(array $data){
    }
}

class Bybit
{
    private $key;
    private $secret;
    private $host;

    protected $type;

    protected $platform=null;
    protected $version='';
    protected $options=[];

    protected $exchange=null;

    function __construct($key,$secret,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=empty($host) ? 'https://api.bybit.com' : $host ;
    }

    function account(){
        $this->exchange=new AccountBybit($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange= new MarketBybit($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange= new TraderBybit($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    /**
    Set exchange transaction category, default "spot" transaction. Other options "spot" "margin" "future" "swap"
     */
    public function setPlatform(string $platform=''){
        $this->platform=empty($platform) ? 'spot' : $platform;
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
        return $this->exchange=$this->trader()->getPlatform();
    }
}
