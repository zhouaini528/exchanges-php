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
    
    function __construct(string $exchange,string $key,string $secret,string $extra='',string $host=''){
        $this->exchange=$exchange;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }
    
    function account(){
        return new Account($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function market(){
        return new Market($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
    
    function trader(){
        return new Trader($this->exchange,$this->key,$this->secret,$this->extra,$this->host);
    }
}