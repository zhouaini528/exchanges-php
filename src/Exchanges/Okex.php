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

class BaseOkex
{
    protected $platform_future;
    protected $platform_spot;
    protected $platform_swap;
    
    function __construct(OkexFuture $platform_future,OkexSpot $platform_spot,OkexSwap $platform_swap){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
        $this->platform_swap=$platform_swap;
    }
    
    protected function checkType($symbol){
        $temp=explode('-', $symbol);
        if(count($temp)>2){
            if(is_numeric($temp[2])) return 'future';
            return 'swap';
        }
        
        return 'spot';
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
                return $this->platform_future->position()->get($data);
            }
            case 'spot':{
                return $this->platform_spot->account()->get($data);
            }
            case 'swap':{
                return $this->platform_swap->position()->get($data);
            }
        }
    }
}

class MarketOkex extends BaseOkex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        
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
                return $this->platform_future->order()->post($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->post($data);
            }
            case 'swap':{
                return $this->platform_swap->order()->post($data);
            }
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                return $this->platform_future->order()->post($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->post($data);
            }
            case 'swap':{
                return $this->platform_swap->order()->post($data);
            }
        }
    }
    
    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                return $this->platform_future->order()->postCancel($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->postCancel($data);
            }
            case 'swap':{
                return $this->platform_swap->order()->postCancel($data);
            }
        }
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
        switch ($this->checkType($data['instrument_id'] ?? '')){
            case 'future':{
                return $this->platform_future->order()->get($data);
            }
            case 'spot':{
                return $this->platform_spot->order()->get($data);
            }
            case 'swap':{
                return $this->platform_swap->order()->get($data);
            }
        }
    }
    
    /**
     *
     * */
    function showAll(array $data){
        
    }
}

class Okex
{
    protected $platform_future;
    protected $platform_spot;
    protected $platform_swap;
    
    function __construct($key,$secret,$passphrase,$host=''){
        $host=empty($host) ? 'https://www.okex.com' : $host ;
        
        $this->platform_future=new OkexFuture($key,$secret,$passphrase,$host);
        
        $this->platform_spot=new OkexSpot($key,$secret,$passphrase,$host);
        
        $this->platform_swap=new OkexSwap($key,$secret,$passphrase,$host);
    }
    
    function account(){
        return new AccountOkex($this->platform_future,$this->platform_spot,$this->platform_swap);
    }
    
    function market(){
        return new MarketOkex($this->platform_future,$this->platform_spot,$this->platform_swap);
    }
    
    function trader(){
        return new TraderOkex($this->platform_future,$this->platform_spot,$this->platform_swap);
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