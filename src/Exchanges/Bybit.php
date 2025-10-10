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
    protected $platform_v5;

    protected $host;

    function __construct(BybitV5 $platform_v5,$host){
        $this->platform_v5=$platform_v5;
        $this->host=$host;
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
        return $this->platform_v5->order()->postCreate($data);
    }

    /**
     *
     * */
    function buy(array $data){
        return $this->platform_v5->order()->postCreate($data);
    }

    /**
     *
     * */
    function cancel(array $data){
        return $this->platform_v5->order()->postCancel($data);
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
        return $this->platform_v5->order()->getRealTime($data);
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

    protected $platform='';
    protected $version='';
    protected $options='';

    protected $platform_v5;

    function __construct($key,$secret,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=empty($host) ? 'https://api.bybit.com' : $host ;

        $this->getPlatform();
    }

    function account(){
        return new AccountBybit($this->platform_v5,$this->host);
    }

    function market(){
        return new MarketBybit($this->platform_v5,$this->host);
    }

    function trader(){
        return new TraderBybit($this->platform_v5,$this->host);
    }

    function getPlatform(string $type=''){
        $this->type=strtolower($type);

        switch ($this->type){
            default:{
                $this->platform_v5=new BybitV5($this->key,$this->secret,$this->host);
                $this->platform_v5->setOptions($this->options);
                return $this->platform_v5;
            }
        }
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
}
