<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Huobi\HuobiSpot;
use Lin\Huobi\HuobiFuture;
use Lin\Huobi\HuobiSwap;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseHuobi
{
    protected $platform_future;
    protected $platform_spot;
    protected $platform_swap;
    
    function __construct(HuobiFuture $platform_future,HuobiSpot $platform_spot,HuobiSwap $platform_swap){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
        $this->platform_swap=$platform_swap;
    }
    
    protected function checkType($symbol){
        if(is_numeric(substr($symbol,-1,1))) return 'future';
        if(stristr($symbol,'-USD')) return 'swap';
        return 'spot';
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
                return $this->platform_future->contract()->postOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->postPlace($data);
            }
            case 'swap':{
                return $this->platform_swap->account()->postOrder($data);
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
                return $this->platform_future->contract()->postOrder($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->postPlace($data);
            }
            case 'swap':{
                return $this->platform_swap->account()->postOrder($data);
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
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postCancel($data);
            }
            case 'spot':{
                if(isset($data['client-order-id'])) return $this->platform_spot->order()->postSubmitCancelClientOrder($data);
                return $this->platform_spot->order()->postSubmitCancel($data);
            }
            case 'swap':{
                return $this->platform_swap->account()->postCancel($data);
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
                $data['symbol']=preg_replace("/\\d+/",'', $data['symbol']);
                return $this->platform_future->contract()->postOrderInfo($data);
            }
            case 'spot':{
                if(isset($data['clientOrderId'])) return $this->platform_spot->order()->getClientOrder($data);
                return $this->platform_spot->order()->get($data);
            }
            case 'swap':{
                return $this->platform_swap->account()->postOrderInfo($data);
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
    protected $platform_future;
    protected $platform_spot;
    protected $platform_swap;
    
    function __construct($key,$secret,$host=''){
        $this->platform_future=new HuobiFuture($key,$secret,empty($host) ? 'https://api.hbdm.com' : $host);
        
        $this->platform_spot=new HuobiSpot($key,$secret,empty($host) ? 'https://api.huobi.pro' : $host);
        
        $this->platform_swap=new HuobiSwap($key,$secret,empty($host) ? 'https://api.hbdm.com' : $host);
    }
    
    function account(){
        return new AccountHuobi($this->platform_future,$this->platform_spot,$this->platform_swap);
    }
    
    function market(){
        return new MarketHuobi($this->platform_future,$this->platform_spot,$this->platform_swap);
    }
    
    function trader(){
        return new TraderHuobi($this->platform_future,$this->platform_spot,$this->platform_swap);
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
                return $this->platform_swap;
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
        $this->platform_swap->setOptions($options);
    }
}