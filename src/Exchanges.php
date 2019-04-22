<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange;

use Lin\Exchange\Api\Account;
use Lin\Exchange\Api\Trader;
use Lin\Exchange\Api\Market;

class Exchanges
{
    protected $exchange='';
    protected $key='';
    protected $secret='';
    protected $extra='';
    protected $host='';
    
    protected $platform;
    
    protected $acount;
    protected $market;
    protected $trader;
    
    function __construct(string $exchange,string $key,string $secret,string $extra='',string $host=''){
        $this->exchange=$exchange;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }
    
    function account(){
        return $this->acount=new Account($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function market(){
        return $this->market=new Market($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function trader(){
        return $this->trader=new Trader($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    /**
     * 支持原生访问
     * */
    public function getPlatform(string $type=''){
        if($this->trader!==null) return $this->trader->getPlatform($type);
        if($this->market!==null) return $this->market->getPlatform($type);
        if($this->acount!==null) return $this->acount->getPlatform($type);
        
        //如果没有就初始化
        return $this->trader()->getPlatform($type);
    }
}