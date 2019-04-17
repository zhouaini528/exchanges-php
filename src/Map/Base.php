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
}


