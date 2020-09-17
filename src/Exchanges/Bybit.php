<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Bybit\BybitInverse;
use Lin\Bybit\BybitLinear;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseBybit
{
    protected $platform_linear;
    protected $platform_inverse;
    protected $host;

    function __construct(BybitLinear $platform_linear=null,BybitInverse $platform_inverse=null,$host){
        $this->platform_linear=$platform_linear;
        $this->platform_inverse=$platform_inverse;
        $this->host=$host;
    }

    protected function checkType(){
        if(stripos($this->host,'bybitlinear')!==false){
            return 'bybitlinear';
        }

        return 'bybitinverse';
    }
}

class AccountBybit extends BaseBybit implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }
}

class MarketBybit extends BaseBybit implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }
}

class TraderBybit extends BaseBybit implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function buy(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function cancel(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function update(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }

    /**
     *
     * */
    function show(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return $this->platform_inverse->order()->get($data);
            }
            case 'bybitlinear':{
                return $this->platform_linear->order()->get($data);
            }
        }
    }

    /**
     *
     * */
    function showAll(array $data){
        switch ($this->checkType()){
            case 'bybitinverse':{
                return ;
            }
            case 'bybitlinear':{
                return ;
            }
        }
    }
}

class Bybit
{
    private $key;
    private $secret;
    private $passphrase;
    private $host;

    protected $type;

    protected $platform_inverse;
    protected $platform_linear;

    function __construct($key,$secret,$passphrase,$host=''){
        $this->key=$key;
        $this->secret=$secret;
        $this->passphrase=$passphrase;
        $this->host=empty($host) ? 'https://api.bybit.com' : $host ;

        $this->getPlatform();
    }

    function account(){
        return new AccountBybit($this->platform_linear,$this->platform_inverse,$this->host);
    }

    function market(){
        return new MarketBybit($this->platform_linear,$this->platform_inverse,$this->host);
    }

    function trader(){
        return new TraderBybit($this->platform_linear,$this->platform_inverse,$this->host);
    }

    function getPlatform(string $type=''){
        $this->type=strtolower($type);

        switch ($this->type){
            case 'bybitinverse':{
                return $this->platform_inverse=new BybitInverse($this->key,$this->secret,$this->host);
            }
            case 'bybitlinear':{
                return $this->platform_linear=new BybitLinear($this->key,$this->secret,$this->host);
            }
            default:{
                if(stripos($this->host,'bybitlinear')!==false){
                    $this->type='bybitlinear';
                    return $this->platform_linear=new BybitLinear($this->key,$this->secret,$this->host);
                }
                $this->type='bybitinverse';
                return $this->platform_inverse=new BybitInverse($this->key,$this->secret,$this->host);
            }
        }
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){

        switch ($this->type){
            case 'bybitinverse':{
                $this->platform_inverse->setOptions($options);
                break;
            }
            case 'bybitlinear':{
                $this->platform_linear->setOptions($options);
                break;
            }
        }
    }
}
