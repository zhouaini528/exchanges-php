<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Binance\Binance as BinanceApi;
use Lin\Binance\BinanceDelivery;
use Lin\Binance\BinanceFuture;

use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBinance
{
    protected $platform_future=null;
    protected $platform_spot=null;
    protected $platform_delivery=null;

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

    protected function checkType(){
        if(stristr($this->host,"fapi")!==false) return 'future';
        if(stristr($this->host,"dapi")!==false) return 'swap';
        return 'spot';
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
            case 'spot':{
                if(empty($this->host)) $this->host='https://api.binance.com';
                if($this->platform_spot == null) $this->platform_spot=new BinanceApi($this->key,$this->secret,$this->host);;
                $this->platform_spot->setOptions($this->options);
                return $this->platform_spot;
            }
            case 'future':{
                if(empty($this->host)) $this->host='https://fapi.binance.com';
                if($this->platform_future == null) $this->platform_future=new BinanceFuture($this->key,$this->secret,$this->host);
                $this->platform_future->setOptions($this->options);
                return $this->platform_future;
            }
            case 'delivery':{
                if(empty($this->host)) $this->host='https://dapi.binance.com';
                if($this->platform_delivery == null) $this->platform_delivery=new BinanceDelivery($this->key,$this->secret,$this->host);
                $this->platform_delivery->setOptions($this->options);
                return $this->platform_delivery;
                return null;
            }
            case 'swap':{
                return null;
            }
            case 'linear':{
                return null;
            }
        }

        return null;
    }
}

class AccountBinance extends BaseBinance implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType()){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->user()->getAccount($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->user()->getAccount($data);
            }
            case 'delivery':{
                return;
            }
        }
    }
}

class MarketBinance extends BaseBinance implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBinance extends BaseBinance implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        switch ($this->checkType()){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->trade()->postOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->trade()->postOrder($data);
            }
            case 'delivery':{
                return;
            }
        }
    }

    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType()){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->trade()->postOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->trade()->postOrder($data);
            }
            case 'delivery':{
                return;
            }
        }
    }

    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType()){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->trade()->deleteOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->trade()->deleteOrder($data);
            }
            case 'delivery':{
                return;
            }
        }
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
        switch ($this->checkType()){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->trade()->getOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->user()->getOrder($data);
            }
            case 'delivery':{
                return;
            }
        }
    }

    /**
     *
     * */
    function showAll(array $data){
        return [];
    }
}

class Binance
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
        $this->exchange=new AccountBinance($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange=new MarketBinance($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange=new TraderBinance($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function getPlatform(string $type=''){
        if($this->exchange==null) {
            $this->setPlatform($type);

            $this->exchange=$this->trader();
        }
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
