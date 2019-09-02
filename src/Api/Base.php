<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Exchanges\Huobi;
use Lin\Exchange\Exchanges\Bitmex;
use Lin\Exchange\Exchanges\Okex;
use Lin\Exchange\Exchanges\Binance;
use Lin\Exchange\Exceptions\Exception;
use Lin\Exchange\Map\Map;
use Lin\Exchange\Exchanges\Ku;


class Base
{
    protected $platform;
    
    protected $map;
    
    /**
     * 初始化交易所
     * */
    function __construct(string $platform,string $key,string $secret,string $extra='',string $host=''){
        $platform=strtolower($platform);
        
        switch ($platform){
            case 'huobi':{
                $this->platform=new Huobi($key,$secret,$host);
                break;
            }
            case 'bitmex':{
                $this->platform=new Bitmex($key,$secret,$host);
                break;
            }
            case 'okex':{
                $this->platform=new Okex($key,$secret,$extra,$host);
                break;
            }
            case 'binance':{
                $this->platform=new Binance($key,$secret,$host);
                break;
            }
            case 'kucoin':{
                $this->platform=new Ku($key,$secret,$extra,$host);
                break;
            }
            default:{
                throw new Exception("Exchanges don't exist");
            }
        }
        
        $this->map=new Map($platform,$key,$secret,$extra,$host);
    }
    
    /**
     *
     * @param 
     * @return array
     * */
    protected function error($msg){
        //是否有请求超时的错误
        if(stripos($msg,'Connection timed out after')!==false){
            $httpcode=504;
        }
        
        $temp=json_decode($msg,true);
        if(!empty($temp) && is_array($temp)) {
            if(isset($httpcode)) $temp['_httpcode']=$httpcode;
            return ['_error'=>$temp];
        }
        
        return [
            '_error'=>$msg,
        ];
    }
    
    /**
     * Returns the underlying instance object
     * */
    function getPlatform(string $type=''){
        return $this->platform->getPlatform($type);
    }
    
    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->platform->setOptions($options);
    }
}