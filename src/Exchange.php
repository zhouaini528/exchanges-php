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
    protected $data=[];
    
    function __construct(string $exchange,array $data){
        $this->exchange=$exchange;
        $this->data=$data;
    }
    
    function account(){
        return new Account($this->exchange,$this->data);
    }
    
    function market(){
        return new Market($this->exchange,$this->data);
    }
    
    function trader(){
        return new Trader($this->exchange,$this->data);
    }
}