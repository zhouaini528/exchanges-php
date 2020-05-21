<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

/**
 * Initialize the mapping object
 * */
class Base
{
    protected $platform;
    protected $key;
    protected $secret;
    protected $extra;
    protected $host;
    
    function __construct(string $platform,string $key,string $secret,string $extra,string $host){
        $this->platform=$platform;
        $this->key=$key;
        $this->secret=$secret;
        $this->extra=$extra;
        $this->host=$host;
    }
    
    /**
     * Native parameter detection
     * As long as the detection data does not have an underscore, it is a native parameter
     * @return true Native parameters.   false Custom parameters
     * */
    protected function checkOriginalParam(array $data){
        foreach ($data as $k=>$v){
            if(stripos($k,'_') === 0) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Detect transaction type
     * @return string  spot future swap
     * */
    protected function checkType(string $symbol=''){
        switch ($this->platform){
            case 'huobi':{
                //Determine if the last digit is a number
                if(is_numeric(substr($symbol,-1,1))) return 'future';
                if(stristr($symbol,'-USD')) return 'swap';
                break;
            }
            case 'bitmex':{
                return 'future';
            }
            case 'okex':{
                //Can be judged spot  future  swap
                $temp=explode('-', $symbol);
                if(count($temp)>2){
                    if(is_numeric($temp[2])) return 'future';
                    return 'swap';
                }
            }
            case 'binance':{
                if(stristr($this->host,"fapi")!==false) return 'future';
            }
            case 'kucoin':{
                if(stripos($this->host,'kumex')!==false){
                    return 'future';
                }
            }
        }
        
        return 'spot';
    }
}


