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
     * @param int 错误类型
     * @return array
     * */
    protected function error($msg){
        $msg=json_decode($msg,true);
        
        return [
            '_error'=>$msg,
        ];
    }
    
    /**
     * 支持原生访问
     * */
    public function getPlatform(){
        return $this->platform;
    }
}