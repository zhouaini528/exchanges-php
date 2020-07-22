<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Ku\Kucoin;
use Lin\Ku\Kumex;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseKu
{
    protected $platform_kumex;
    protected $platform_kucoin;
    protected $host;

    function __construct(Kumex $platform_kumex=null,Kucoin $platform_kucoin=null,$host){
        $this->platform_kumex=$platform_kumex;
        $this->platform_kucoin=$platform_kucoin;
        $this->host=$host;
    }

    protected function checkType(){
        if(stripos($this->host,'kumex')!==false){
            return 'kumex';
        }

        return 'kucoin';
    }
}

class AccountKu extends BaseKu implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                if(empty($data)) return $this->platform_kucoin->account()->getAll();
                return $this->platform_kucoin->account()->get($data);
            }
            case 'kumex':{
                if(empty($data)) return $this->platform_kumex->position()->getAll();
                return $this->platform_kumex->position()->get($data);
            }
        }
    }
}

class MarketKu extends BaseKu implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return ;
            }
            case 'kumex':{
                return ;
            }
        }
    }
}

class TraderKu extends BaseKu implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return $this->platform_kucoin->order()->post($data);
            }
            case 'kumex':{
                return $this->platform_kumex->order()->post($data);
            }
        }
    }

    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return $this->platform_kucoin->order()->post($data);
            }
            case 'kumex':{
                return $this->platform_kumex->order()->post($data);
            }
        }
    }

    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return $this->platform_kucoin->order()->delete($data);
            }
            case 'kumex':{
                return $this->platform_kumex->order()->delete($data);
            }
        }
    }

    /**
     *
     * */
    function update(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return ;
            }
            case 'kumex':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function show(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return $this->platform_kucoin->order()->get($data);
            }
            case 'kumex':{
                return $this->platform_kumex->order()->get($data);
            }
        }
    }

    /**
     *
     * */
    function showAll(array $data){
        switch ($this->checkType()){
            case 'kucoin':{
                return ;
            }
            case 'kumex':{
                return ;
            }
        }
    }
}

class Ku
{
    private $key;
    private $secret;
    private $passphrase;
    private $host;

    protected $type;

    protected $platform_kucoin;
    protected $platform_kumex;

    function __construct($key,$secret,$passphrase,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->passphrase=$passphrase;
        $this->host=empty($host) ? 'https://api.kucoin.com' : $host ;

        $this->getPlatform();
    }

    function account(){
        return new AccountKu($this->platform_kumex,$this->platform_kucoin,$this->host);
    }

    function market(){
        return new MarketKu($this->platform_kumex,$this->platform_kucoin,$this->host);
    }

    function trader(){
        return new TraderKu($this->platform_kumex,$this->platform_kucoin,$this->host);
    }

    function getPlatform(string $type=''){
        $this->type=strtolower($type);

        switch ($this->type){
            case 'kucoin':{
                return $this->platform_kucoin=new Kucoin($this->key,$this->secret,$this->passphrase,$this->host);
            }
            case 'kumex':{
                return $this->platform_kumex=new Kumex($this->key,$this->secret,$this->passphrase,$this->host);
            }
            default:{
                if(stripos($this->host,'kumex')!==false){
                    $this->type='kumex';
                    return $this->platform_kumex=new Kumex($this->key,$this->secret,$this->passphrase,$this->host);
                }
                $this->type='kucoin';
                return $this->platform_kucoin=new Kucoin($this->key,$this->secret,$this->passphrase,$this->host);
            }
        }
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){

        switch ($this->type){
            case 'kucoin':{
                $this->platform_kucoin->setOptions($options);
                break;
            }
            case 'kumex':{
                $this->platform_kumex->setOptions($options);
                break;
            }
        }
    }
}
