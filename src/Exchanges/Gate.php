<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

use Lin\Gate\GateFuture;
use Lin\Gate\GateDelivery;
use Lin\Gate\GateSpot;
use Lin\Gate\GateSpotV2;
use Lin\Gate\GateMargin;
use Lin\Gate\GateWallet;

class BaseGate
{
    protected $platform_future;
    protected $platform_delivery;
    protected $platform_spot;
    protected $platform_spot_v2;
    
    
    protected $platform_margin;
    protected $platform_wallet;
    
    function __construct(
        GateFuture $platform_future,
        GateDelivery $platform_delivery,
        GateSpot $platform_spot,
        GateSpotV2 $platform_spot_v2,
        GateMargin $platform_margin,
        GateWallet $platform_wallet){
        $this->platform_future=$platform_future;
        $this->platform_delivery=$platform_delivery;
        $this->platform_spot=$platform_spot;
        $this->platform_spot_v2=$platform_spot_v2;
        
        $this->platform_margin= $platform_margin;
        $this->platform_wallet= $platform_wallet;
    }
}

class AccountGate extends BaseGate implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketGate extends BaseGate implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderGate extends BaseGate implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        return [];
    }
    
    /**
     *
     * */
    function buy(array $data){
        return [];
    }
    
    /**
     *
     * */
    function cancel(array $data){
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
        return [];
    }
    
    /**
     *
     * */
    function showAll(array $data){
        return [];
    }
}

class Gate
{
    protected $platform_future;
    protected $platform_delivery;
    protected $platform_spot;
    protected $platform_spot_v2;
    
    
    protected $platform_margin;
    protected $platform_wallet;
    
    function __construct($key,$secret,$host=''){
        $this->platform_future=new GateFuture($key,$secret,$host);
        $this->platform_delivery=new GateDelivery($key,$secret,$host);
        $this->platform_spot=new GateSpot($key,$secret,$host);
        $this->platform_spot_v2=new GateSpotV2($key,$secret,$host);
        
        $this->platform_margin=new GateMargin($key,$secret,$host);
        $this->platform_wallet=new GateWallet($key,$secret,$host);
    }
    
    function account(){
        return new AccountGate(
            $this->platform_future,
            $this->platform_delivery,
            $this->platform_spot,
            $this->platform_spot_v2,
            $this->platform_margin,
            $this->platform_wallet);
    }
    
    function market(){
        return new MarketGate($this->platform_future,
            $this->platform_delivery,
            $this->platform_spot,
            $this->platform_spot_v2,
            $this->platform_margin,
            $this->platform_wallet);
    }
    
    function trader(){
        return new TraderGate($this->platform_future,
            $this->platform_delivery,
            $this->platform_spot,
            $this->platform_spot_v2,
            $this->platform_margin,
            $this->platform_wallet);
    }
    
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'spot':{
                return $this->platform_spot;
            }
            case 'spot_v2':{
                return $this->platform_spot_v2;
            }
            case 'future':{
                return $this->platform_future;
            }
            case 'margin':{
                return $this->platform_margin;
            }
            case 'delivery':{
                return $this->platform_delivery;
            }
            case 'wallet':{
                return $this->platform_wallet;
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
        $this->platform_spot_v2->setOptions($options);
        $this->platform_delivery->setOptions($options);
        
        $this->platform_margin->setOptions($options);
        $this->platform_wallet->setOptions($options);
    }
}