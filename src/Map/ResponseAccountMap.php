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
    function get(array $data){
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
            case 'kucoin':{
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
}

