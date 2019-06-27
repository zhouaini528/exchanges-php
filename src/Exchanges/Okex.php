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

class BaseOkex
{
    protected $platform_future;
    protected $platform_spot;
    
    function __construct(OkexFuture $platform_future,OkexSpot $platform_spot){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
    }
}

class AccountOkex extends BaseOkex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        if(isset($data['instrument_id'])){
            return $this->platform_future->position()->get($data);
        }else{
            return $this->platform_spot->account()->get($data);
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
        //判断是期货还是现货
        if(isset($data['match_price'])){
            return $this->platform_future->order()->post($data);
        }else{
            return $this->platform_spot->order()->post($data);
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        //判断是期货还是现货
        if(isset($data['match_price'])){
            return $this->platform_future->order()->post($data);
        }else{
            return $this->platform_spot->order()->post($data);
        }
    }
    
    /**
     *
     * */
    function cancel(array $data){
        $instrument=explode('-', $data['instrument_id']);
        if(count($instrument)>2){
            return $this->platform_future->order()->postCancel($data);
        }else{
            return $this->platform_spot->order()->postCancel($data);
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
        //BTC-USD-190628
        //BTC-USD-SWAP
        //BTC-USD
        $instrument=explode('-', $data['instrument_id']);
        if(count($instrument)>2){
            return $this->platform_future->order()->get($data);
        }else{
            return $this->platform_spot->order()->get($data);
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
    
    
    function __construct($key,$secret,$passphrase,$host=''){
        $host=empty($host) ? 'https://www.okex.com' : $host ;
        
        $this->platform_future=new OkexFuture($key,$secret,$passphrase,$host);
        
        $this->platform_spot=new OkexSpot($key,$secret,$passphrase,$host);
    }
    
    function account(){
        return new AccountOkex($this->platform_future,$this->platform_spot);
    }
    
    function market(){
        return new MarketOkex($this->platform_future,$this->platform_spot);
    }
    
    function trader(){
        return new TraderOkex($this->platform_future,$this->platform_spot);
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
     * Local development sets the proxy
     * @param bool|array
     * $proxy=false Default
     * $proxy=true  Local proxy http://127.0.0.1:12333
     *
     * Manual proxy
     * $proxy=[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     * ]
     *
     * @param mixed
     * */
    function setProxy($proxy=true){
        $this->platform_future->setProxy($proxy);
        $this->platform_spot->setProxy($proxy);
    }
}