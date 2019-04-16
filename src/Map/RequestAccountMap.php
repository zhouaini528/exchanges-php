<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

use Lin\Exchange\Interfaces\AccountInterface;


/**
 * 账户接口参数映射
 * */
class RequestAccountMap extends Base implements AccountInterface
{
    /**
     *
     * */
    function position(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                break;
            }
            case 'bitmex':{
                break;
            }
            case 'okex':{
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        return $map;
    }
}


