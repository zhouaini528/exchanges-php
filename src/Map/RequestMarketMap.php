<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

use Lin\Exchange\Interfaces\MarketInterface;

/**
 * 行情接口参数映射
 * */
class RequestMarketMap extends Base implements MarketInterface
{
    /**
     *
     * */
    function depth(array $data){
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


