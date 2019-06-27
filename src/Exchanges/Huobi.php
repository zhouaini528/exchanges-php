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
}

class AccountHuobi extends BaseHuobi implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        if(isset($data['contract_type'])){
            return [];
        }else{
            return $this->platform_spot->account()->getBalance($data);
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
        if(isset($data['contract_type'])){
            return $this->platform_future->contract()->postOrder($data);
        }else{
            return $this->platform_spot->order()->postPlace($data);
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        if(isset($data['contract_type'])){
            return $this->platform_future->contract()->postOrder($data);
        }else{
            return $this->platform_spot->order()->postPlace($data);
        }
    }
    
    /**
     *
     * */
    function cancel(array $data){
        if(isset($data['contract_type'])){
            return $this->platform_future->contract()->postCancel($data);
        }else{
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
        if(isset($data['contract_type'])){
            return $this->platform_future->contract()->getContractInfo($data);
        }else{
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
        $host=empty($host) ? 'https://api.huobi.pro' : $host ;
        
        $this->platform_future=new HuobiFuture($key,$secret,$host);
        
        $this->platform_spot=new HuobiSpot($key,$secret,$host);
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