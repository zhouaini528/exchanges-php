<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

/**
 * 初始化映射对象
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
     * 原生参数检测
     * 只要检测数据没有`_`下划线 即为原生参数
     * @return true 原生参数   false自定义参数
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
     * 检测交易类型
     * @return string  spot future swap
     * */
    protected function checkType(string $symbol=''){
        switch ($this->platform){
            case 'huobi':{
                //判断最后一位是否是数字
                if(is_numeric(substr($symbol,-1,1))) return 'future';
                if(stristr($symbol,'-USD')) return 'swap';
                break;
            }
            case 'bitmex':{
                return 'future';
            }
            case 'okex':{
                //可以通过判断  spot  future  swap
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


