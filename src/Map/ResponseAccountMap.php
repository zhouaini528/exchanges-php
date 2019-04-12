<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

use Lin\Exchange\Interfaces\AccountInterface;

/**
 * 账户接口参数映射
 * */
class ResponseAccountMap extends Base implements AccountInterface
{
    /**
     *
     * */
    function position(array $data){
        return $data;
    }
}

