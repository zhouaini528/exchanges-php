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


class Base
{
    protected $platform;
    
    protected $map;
    
    protected $proxy=false;
    
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
     * 
     * */
    function getPlatform(string $type=''){
        return $this->platform->getPlatform($type);
    }
    
    /**
     * Local development sets the proxy
     * @param bool|array
     * $proxy=false Default
     * $proxy=true  Local proxy http://127.0.0.1:12333
     *
     * Manual proxy
     * $proxy=[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     * ]
     * 
     * @param mixed
     * */
    function setProxy($proxy=true){
        $this->platform->setProxy($proxy);
    }
    
    /**
     * Set the request timeout to 60 seconds by default
     * */
    function setTimeOut($timeout=60){
        $this->platform->setTimeOut($timeout);
    }
}