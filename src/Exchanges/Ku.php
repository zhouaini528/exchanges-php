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
    protected $platform_kucoin;
    protected $platform_kumex;
    
    function __construct(Kumex $platform_kumex,Kucoin $platform_kucoin){
        $this->platform_kumex=$platform_kumex;
        $this->platform_kucoin=$platform_kucoin;
    }
}

class AccountKu extends BaseKu implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        throw new \Exception('Temporarily not supported');
    }
}

class MarketKu extends BaseKu implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        throw new \Exception('Temporarily not supported');
    }
}

class TraderKu extends BaseKu implements TraderInterface
{
    /**
     *
     * */
    function sell(array $data){
        throw new \Exception('Temporarily not supported');
    }
    
    /**
     *
     * */
    function buy(array $data){
        throw new \Exception('Temporarily not supported');
    }
    
    /**
     *
     * */
    function cancel(array $data){
        throw new \Exception('Temporarily not supported');
    }
    
    /**
     *
     * */
    function update(array $data){
        throw new \Exception('Temporarily not supported');
    }
    
    /**
     *
     * */
    function show(array $data){
        throw new \Exception('Temporarily not supported');
    }
    
    /**
     *
     * */
    function showAll(array $data){
        throw new \Exception('Temporarily not supported');
    }
}

class Ku
{
    protected $platform_kucoin;
    protected $platform_kumex;
    protected $host;
    
    function __construct($key,$secret,$passphrase,$host=''){
        $this->host=empty($host) ? 'https://api.kucoin.com' : $host ;
        
        $this->platform_kucoin=new Kucoin($key,$secret,$passphrase,$this->host);
        
        $this->platform_kumex=new Kumex($key,$secret,$passphrase,$this->host);
    }
    
    function account(){
        return new AccountKu($this->platform_kumex,$this->platform_kucoin);
    }
    
    function market(){
        return new MarketKu($this->platform_kumex,$this->platform_kucoin);
    }
    
    function trader(){
        return new TraderKu($this->platform_kumex,$this->platform_kucoin);
    }
    
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'kucoin':{
                return $this->platform_kucoin;
            }
            case 'kumex':{
                return $this->platform_kumex;
            }
            default:{
                if(stripos($this->host,'kumex')!==0) {
                    return $this->platform_kumex;
                }
                return $this->platform_kucoin;
            }
        }
    }
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform_kucoin->setOptions($options);
        $this->platform_kumex->setOptions($options);
    }
}