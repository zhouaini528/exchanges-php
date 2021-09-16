<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Okex\OkexFuture;
use Lin\Okex\OkexSpot;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;
use Lin\Okex\OkexSwap;

use Lin\Okex\OkexAccount;
use Lin\Okex\OkexMargin;
use Lin\Okex\OkexOption;

class BaseOkex
{
    protected $platform_future=null;
    protected $platform_spot=null;
    protected $platform_swap=null;

    protected $platform_account=null;
    protected $platform_margin=null;
    protected $platform_option=null;

    protected $platform='';
    protected $version='';
    protected $options='';

    protected $key;
    protected $secret;
    protected $passphrase;
    protected $host='';

    function __construct($key,$secret,$passphrase,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->passphrase=$passphrase;
        $this->host=$host;
    }

    protected function checkType($symbol){
        if(empty($this->platform)){
            $temp=explode('-', $symbol);
            if(count($temp)>2){
                if(is_numeric($temp[2])) return 'future';
                return 'swap';
            }

            return 'spot';
        }else{
            switch ($this->platform){
                case 'future':return 'future';
                case 'spot':return 'spot';
                case 'swap':return 'swap';
            }
        }
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
        /*$this->platform_swap=new OkexSwap($key,$secret,$passphrase,$host);
        $this->platform_account=new OkexAccount($key,$secret,$passphrase,$host);
        $this->platform_margin=new OkexMargin($key,$secret,$passphrase,$host);
        $this->platform_option=new OkexOption($key,$secret,$passphrase,$host);*/

        switch (strtolower($type)){
            case 'margin':
            case 'spot':{
                if($this->platform_spot == null) $this->platform_spot=$this->platform_spot=new OkexSpot($this->key,$this->secret,$this->passphrase,$this->host);
                $this->platform_spot->setOptions($this->options);
                return $this->platform_spot;
            }
            case 'future':{
                if($this->platform_future == null) $this->platform_future=new OkexFuture($this->key,$this->secret,$this->passphrase,$this->host);
                $this->platform_future->setOptions($this->options);
                return $this->platform_future;
            }
            /*case 'swap':{
                if($this->platform_swap == null) $this->platform_swap=new HuobiSwap($this->key,$this->secret,empty($this->host) ? 'https://api.hbdm.com' : $this->host);
                $this->platform_swap->setOptions($this->options);
                return $this->platform_swap;
            }
            case 'linear':{
                if($this->platform_linear == null) $this->platform_linear=new HuobiLinear($this->key,$this->secret,empty($this->host) ? 'https://api.hbdm.com' : $this->host);
                $this->platform_linear->setOptions($this->options);
                return $this->platform_linear;
            }*/
        }

        return null;
    }
}

class AccountOkex extends BaseOkex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->position()->get($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->account()->get($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('spot');
                return $this->platform_swap->position()->get($data);
            }
        }

        return [];
    }
}

class MarketOkex extends BaseOkex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderOkex extends BaseOkex implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->order()->post($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->post($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('spot');
                return $this->platform_swap->order()->post($data);
            }
        }

        return [];
    }

    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->order()->post($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->post($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('spot');
                return $this->platform_swap->order()->post($data);
            }
        }

        return [];
    }

    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->order()->postCancel($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->postCancel($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('spot');
                return $this->platform_swap->order()->postCancel($data);
            }
        }

        return [];
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
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->order()->get($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->get($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('spot');
                return $this->platform_swap->order()->get($data);
            }
        }

        return [];
    }

    /**
     *
     * */
    function showAll(array $data){

    }
}

class Okex
{
    protected $platform='';
    protected $version='';
    protected $options=[];

    protected $key;
    protected $secret;
    protected $passphrase;
    protected $host='';

    protected $exchange=null;

    function __construct($key,$secret,$passphrase,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->passphrase=$passphrase;
        $this->host= empty($host) ? 'https://www.okex.com' : $host ;
    }

    function account(){
        $this->exchange= new AccountOkex($this->key,$this->secret,$this->passphrase,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange= new MarketOkex($this->key,$this->secret,$this->passphrase,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange= new TraderOkex($this->key,$this->secret,$this->passphrase,$this->host);
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
