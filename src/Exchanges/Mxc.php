<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;

use Lin\Mxc\MxcSpot;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseMxc
{
    protected $platform_spot;
    
    function __construct(MxcSpot $platform_spot){
        $this->platform_spot=$platform_spot;
    }
}

class AccountMxc extends BaseMxc implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketMxc extends BaseMxc implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderMxc extends BaseMxc implements TraderInterface
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

class Mxc
{
    protected $platform_spot;
    
    function __construct($key,$secret,$host=''){
        $this->platform_spot=new MxcSpot($key,$secret,empty($host) ? 'https://www.mxc.co' : $host);
    }
    
    function account(){
        return new AccountMxc($this->platform_spot);
    }
    
    function market(){
        return new MarketMxc($this->platform_spot);
    }
    
    function trader(){
        return new TraderMxc($this->platform_spot);
    }
    
    function getPlatform(string $type=''){
        switch (strtolower($type)){
            case 'spot':{
                return $this->platform_spot;
            }
            default:{
                return $this->platform_spot;
            }
        }
    }
    
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform_spot->setOptions($options);
    }
}