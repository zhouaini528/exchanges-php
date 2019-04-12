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
    
    function __construct(string $platform){
        $this->platform=$platform;
    }
}


