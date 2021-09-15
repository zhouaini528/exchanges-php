<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Huobi\HuobiSpot;
use Lin\Huobi\HuobiFuture;
use Lin\Huobi\HuobiSwap;
use Lin\Huobi\HuobiLinear;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseHuobi
{
    protected $platform_future=null;
    protected $platform_spot=null;
    protected $platform_swap=null;
    protected $platform_linear=null;

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

    protected function checkType($symbol){
        if(empty($this->platform)) {
            if(is_numeric(substr($symbol,-1,1))) return 'future';
            if(stristr($symbol,'-USD')) return 'swap';
        }else{
            switch ($this->platform){
                case 'spot':return 'spot';
                case 'margin':return 'margin';
                case 'future':return 'future';
                case 'swap':{
                    if(stristr($symbol,'-USDT')) return 'linear';
                    return 'swap';
                }
            }
        }

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
                if($this->platform_spot == null) $this->platform_spot=new HuobiSpot($this->key,$this->secret,empty($this->host) ? 'https://api.huobi.pro' : $this->host);
                $this->platform_spot->setOptions($this->options);
                return $this->platform_spot;
            }
            case 'future':{
                if($this->platform_future == null) $this->platform_future=new HuobiFuture($this->key,$this->secret,empty($this->host) ? 'https://api.hbdm.com' : $this->host);
                $this->platform_future->setOptions($this->options);
                return $this->platform_future;
            }
            case 'swap':{
                if($this->platform_swap == null) $this->platform_swap=new HuobiSwap($this->key,$this->secret,empty($this->host) ? 'https://api.hbdm.com' : $this->host);
                $this->platform_swap->setOptions($this->options);
                return $this->platform_swap;
            }
            case 'linear':{
                if($this->platform_linear == null) $this->platform_linear=new HuobiLinear($this->key,$this->secret,empty($this->host) ? 'https://api.hbdm.com' : $this->host);
                $this->platform_linear->setOptions($this->options);
                return $this->platform_linear;
            }
        }

        return null;
    }
}

class AccountHuobi extends BaseHuobi implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        $temp='';
        if(isset($data['symbol'])) $temp=$data['symbol'];
        if(isset($data['contract_code'])) $temp=$data['contract_code'];

        switch ($this->checkType($temp)){
            case 'future':{
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postPositionInfo($data);
            }
            case 'spot':{
                return $this->platform_spot->account()->getBalance($data);
            }
            case 'swap':{
                return null;
            }
        }
    }
}

class MarketHuobi extends BaseHuobi implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderHuobi extends BaseHuobi implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        $temp='';
        if(isset($data['symbol'])) $temp=$data['symbol'];
        if(isset($data['contract_code'])) $temp=$data['contract_code'];

        switch ($this->checkType($temp)){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->contract()->postOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->postPlace($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('swap');
                return $this->platform_swap->trade()->postOrder($data);
            }
            case 'linear':{
                $this->platform_linear=$this->getPlatform('linear');
                return $this->platform_linear->trade()->postOrder($data);
            }

        }
    }

    /**
     *
     * */
    function buy(array $data){
        $temp='';
        if(isset($data['symbol'])) $temp=$data['symbol'];
        if(isset($data['contract_code'])) $temp=$data['contract_code'];

        switch ($this->checkType($temp)){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                return $this->platform_future->contract()->postOrder($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                return $this->platform_spot->order()->postPlace($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('swap');
                return $this->platform_swap->trade()->postOrder($data);
            }
            case 'linear':{
                $this->platform_linear=$this->getPlatform('linear');
                return $this->platform_linear->trade()->postOrder($data);
            }
        }
    }

    /**
     *
     * */
    function cancel(array $data){
        $temp='';
        if(isset($data['symbol'])) $temp=$data['symbol'];
        if(isset($data['contract_code'])) $temp=$data['contract_code'];

        switch ($this->checkType($temp)){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postCancel($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                if(isset($data['client-order-id'])) return $this->platform_spot->order()->postSubmitCancelClientOrder($data);
                return $this->platform_spot->order()->postSubmitCancel($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('swap');
                return $this->platform_swap->trade()->postCancel($data);
            }
            case 'linear':{
                $this->platform_linear=$this->getPlatform('linear');
                return $this->platform_linear->trade()->postCancel($data);
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
        $temp='';
        if(isset($data['symbol'])) $temp=$data['symbol'];
        if(isset($data['contract_code'])) $temp=$data['contract_code'];

        switch ($this->checkType($temp)){
            case 'future':{
                $this->platform_future=$this->getPlatform('future');
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postOrderInfo($data);
            }
            case 'spot':{
                $this->platform_spot=$this->getPlatform('spot');
                //order-id
                if(isset($data['order-id']) && !empty($data['order-id'])) return $this->platform_spot->order()->get($data);
                return $this->platform_spot->order()->getClientOrder($data);
            }
            case 'swap':{
                $this->platform_swap=$this->getPlatform('swap');
                return $this->platform_swap->trade()->postOrderInfo($data);
            }
            case 'linear':{
                $this->platform_linear=$this->getPlatform('linear');
                return $this->platform_linear->trade()->postOrderInfo($data);
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

class Huobi
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
        $this->exchange=new AccountHuobi($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange=new MarketHuobi($this->key,$this->secret,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange = new TraderHuobi($this->key,$this->secret,$this->host);
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
