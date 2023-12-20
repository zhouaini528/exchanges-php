<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Ku\Kucoin as KucoinSpot;
use Lin\Ku\KucoinFuture;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class Base
{
    protected $platform_future;
    protected $platform_spot;
    protected $host;

    function __construct(KucoinFuture $platform_future=null,KucoinSpot $platform_spot=null,$host){
        $this->platform_future=$platform_future;
        $this->platform_spot=$platform_spot;
        $this->host=$host;
    }

    protected function checkType(){
        if(stripos($this->host,'future')!==false){
            return 'future';
        }

        return 'spot';
    }
}

class AccountKucoin extends Base implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType()){
            case 'spot':{
                if(empty($data)) return $this->platform_spot->account()->getAll();
                return $this->platform_spot->account()->get($data);
            }
            case 'future':{
                if(empty($data)) return $this->platform_future->position()->getAll();
                return $this->platform_future->position()->get($data);
            }
        }
    }
}

class MarketKucoin extends Base implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return ;
            }
            case 'future':{
                return ;
            }
        }
    }
}

class TraderKucoin extends Base implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return $this->platform_spot->order()->post($data);
            }
            case 'future':{
                return $this->platform_future->order()->post($data);
            }
        }
    }

    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return $this->platform_spot->order()->post($data);
            }
            case 'future':{
                return $this->platform_future->order()->post($data);
            }
        }
    }

    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return $this->platform_spot->order()->delete($data);
            }
            case 'future':{
                return $this->platform_future->order()->delete($data);
            }
        }
    }

    /**
     *
     * */
    function update(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return ;
            }
            case 'future':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function show(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return $this->platform_spot->order()->get($data);
            }
            case 'future':{
                return $this->platform_future->order()->get($data);
            }
        }
    }

    /**
     *
     * */
    function showAll(array $data){
        switch ($this->checkType()){
            case 'spot':{
                return ;
            }
            case 'future':{
                return ;
            }
        }
    }
}

class Kucoin
{
    private $key;
    private $secret;
    private $passphrase;
    private $host;

    protected $type;

    protected $platform_spot;
    protected $platform_future;

    function __construct($key,$secret,$passphrase,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->passphrase=$passphrase;
        $this->host=empty($host) ? 'https://api.kucoin.com' : $host ;

        $this->getPlatform();
    }

    function account(){
        return new AccountKucoin($this->platform_future,$this->platform_spot,$this->host);
    }

    function market(){
        return new MarketKucoin($this->platform_future,$this->platform_spot,$this->host);
    }

    function trader(){
        return new TraderKucoin($this->platform_future,$this->platform_spot,$this->host);
    }

    function getPlatform(string $type=''){
        $this->type=strtolower($type);

        switch ($this->type){
            case 'spot':{
                return $this->platform_spot=new KucoinSpot($this->key,$this->secret,$this->passphrase,$this->host);
            }
            case 'future':{
                $this->host='https://api-futures.kucoin.com';
                return $this->platform_future=new KucoinFuture($this->key,$this->secret,$this->passphrase,$this->host);
            }
            default:{
                if(stripos($this->host,'future')!==false){
                    $this->type='future';
                    return $this->platform_future=new KucoinFuture($this->key,$this->secret,$this->passphrase,$this->host);
                }
                $this->type='spot';
                return $this->platform_spot=new KucoinSpot($this->key,$this->secret,$this->passphrase,$this->host);
            }
        }
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
