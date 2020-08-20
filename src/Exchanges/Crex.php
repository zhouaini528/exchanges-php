<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Exchanges;


use Lin\Crex\Crex as CrexApi;
use Lin\Exchange\Interfaces\AccountInterface;
use Lin\Exchange\Interfaces\MarketInterface;
use Lin\Exchange\Interfaces\TraderInterface;

class BaseCrex
{
    protected $platform;

    function __construct(CrexApi $platform){
        $this->platform=$platform;
    }
}

class AccountCrex extends BaseCrex implements AccountInterface
{
    /**
     *
     * */
    function get(array $data){
        return [];
    }
}

class MarketCrex extends BaseCrex implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
        return [];
    }
}

class TraderCrex extends BaseCrex implements TraderInterface
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

class Crex
{
    protected $platform;

    function __construct($key,$secret,$host=''){
        $host=empty($host) ? 'https://api.crex24.com' : $host ;

        $this->platform=new CrexApi($key,$secret,$host);
    }

    function account(){
        return new AccountCrex($this->platform);
    }

    function market(){
        return new MarketCrex($this->platform);
    }

    function trader(){
        return new TraderCrex($this->platform);
    }

    function getPlatform(string $type=''){
        return $this->platform;
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform->setOptions($options);
    }
}
