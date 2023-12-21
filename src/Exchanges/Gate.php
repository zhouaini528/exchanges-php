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
    private $key;
    private $secret;
    private $host;

    protected $type;

    protected $platform_future;
    protected $platform_delivery;
    protected $platform_spot;
    protected $platform_spot_v2;


    protected $platform_margin;
    protected $platform_wallet;

    protected $platform;

    protected $version;

    function __construct($key,$secret,$host){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=empty($host) ? 'https://api.gateio.ws' : '';
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
        $this->type=strtolower($type);

        switch ($this->type){
            case 'spot_v2':{
                $this->host='https://api.gateio.la';
                return $this->platform_spot_v2=new GateSpotV2($this->key,$this->secret,$this->host);
            }
            case 'future':{
                return $this->platform_future=new GateFuture($this->key,$this->secret,$this->host);
            }
            case 'margin':{
                return $this->platform_margin=new GateMargin($this->key,$this->secret,$this->host);
            }
            case 'delivery':{
                return $this->platform_delivery=new GateDelivery($this->key,$this->secret,$this->host);
            }
            case 'wallet':{
                return $this->platform_wallet=new GateWallet($this->key,$this->secret,$this->host);
            }
            case 'spot':
            default:{
                return $this->platform_spot=new GateSpot($this->key,$this->secret,$this->host);
            }
        }
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        switch ($this->type){
            case 'spot':{
                $this->platform_spot->setOptions($options);
                break;
            }
            case 'spot_v2':{
                $this->platform_spot_v2->setOptions($options);
                break;
            }
            case 'future':{
                $this->platform_future->setOptions($options);
                break;
            }
            case 'margin':{
                $this->platform_margin->setOptions($options);
                break;
            }
            case 'delivery':{
                $this->platform_delivery->setOptions($options);
                break;
            }
            case 'wallet':{
                $this->platform_wallet->setOptions($options);
                break;
            }
            default:{

            }
        }

        return $this;
    }

    /**
    Set exchange transaction category, default "spot" transaction. Other options "spot" "margin" "future" "swap"
     */
    public function setPlatform(string $platform=''){
        $this->platform=$platform ?? 'spot';
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
    /*function setOptions(array $options=[]){
        $this->options=$options;
        return $this;
    }*/
}
