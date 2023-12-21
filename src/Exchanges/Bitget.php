<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;
use Lin\Bitget\BitgetContractV2;
use Lin\Bitget\BitgetSpotV2;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;


class BaseBitget
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
        return '';
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
            case 'contract':
            case 'future':{
                if($this->platform_future == null) $this->platform_future=new BitgetContractV2($this->key,$this->secret,$this->passphrase,$this->host);
                $this->platform_future->setOptions($this->options);
                return $this->platform_future;
            }
            case 'spot':
            default:{
                if($this->platform_spot == null) $this->platform_spot=new BitgetSpotV2($this->key,$this->secret,$this->passphrase,$this->host);
                $this->platform_spot->setOptions($this->options);
                return $this->platform_spot;
            }
        }
        return null;
    }
}

class AccountBitget extends BaseBitget implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketBitget extends BaseBitget implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderBitget extends BaseBitget implements TraderInterface
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

class Bitget
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
        $this->host= empty($host) ? 'https://api.bitget.com' : $host ;
    }

    function account(){
        $this->exchange= new AccountBitget($this->key,$this->secret,$this->passphrase,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function market(){
        $this->exchange= new MarketBitget($this->key,$this->secret,$this->passphrase,$this->host);
        $this->exchange->setPlatform($this->platform)->setVersion($this->version)->setOptions($this->options);
        return $this->exchange;
    }

    function trader(){
        $this->exchange= new TraderBitget($this->key,$this->secret,$this->passphrase,$this->host);
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
    function setOptions(array $options=[]){
        $this->options=$options;
        return $this;
    }
}
