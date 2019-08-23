<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Huobi\HuobiSpot;
use Lin\Huobi\HuobiFuture;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseHuobi
{
    protected $platform_future;
    protected $platform_spot;
    
    function __construct(HuobiFuture $platform_future,HuobiSpot $platform_spot){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
    }
    
    protected function checkType($symbol){
        if(is_numeric(substr($symbol,-1,1))) return 'future';
        return 'spot';
    }
}

class AccountHuobi extends BaseHuobi implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType($data['symbol'])){
            case 'future':{
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postPositionInfo($data);
            }
            case 'spot':{
                return $this->platform_spot->account()->getBalance($data);
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
        if(isset($data['contract_code'])){
            return $this->platform_future->contract()->postOrder($data);
        }else{
            return $this->platform_spot->order()->postPlace($data);
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        if(isset($data['contract_code'])){
            return $this->platform_future->contract()->postOrder($data);
        }else{
            return $this->platform_spot->order()->postPlace($data);
        }
    }
    
    /**
     *
     * */
    function cancel(array $data){
        if(isset($data['symbol']) && !empty($data['symbol'])){
            $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
            return $this->platform_future->contract()->postCancel($data);
        }else{
            if(isset($data['client-order-id'])) return $this->platform_spot->order()->postSubmitCancelClientOrder($data);
            return $this->platform_spot->order()->postSubmitCancel($data);
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
        if(isset($data['symbol']) && !empty($data['symbol'])){
            $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
            return $this->platform_future->contract()->postOrderInfo($data);
        }else{
            if(isset($data['clientOrderId'])) return $this->platform_spot->order()->getClientOrder($data);
            return $this->platform_spot->order()->get($data);
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
    protected $platform_future;
    protected $platform_spot;
    
    function __construct($key,$secret,$host=''){
        $this->platform_future=new HuobiFuture($key,$secret,empty($host) ? 'https://api.hbdm.com' : $host);
        
        $this->platform_spot=new HuobiSpot($key,$secret,empty($host) ? 'https://api.huobi.pro' : $host);
    }
    
    function account(){
        return new AccountHuobi($this->platform_future,$this->platform_spot);
    }
    
    function market(){
        return new MarketHuobi($this->platform_future,$this->platform_spot);
    }
    
    function trader(){
        return new TraderHuobi($this->platform_future,$this->platform_spot);
    }
    
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'spot':{
                return $this->platform_spot;
            }
            case 'future':{
                return $this->platform_future;
            }
            case 'swap':{
                return null;
            }
            default:{
                return null;
            }
        }
    }
    
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform_future->setOptions($options);
        $this->platform_spot->setOptions($options);
    }
}