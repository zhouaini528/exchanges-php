<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Exchanges\Huobi;
use Lin\Exchange\Exchanges\Bitmex;
use Lin\Exchange\Exchanges\Okex;
use Lin\Exchange\Exchanges\Binance;

class Base
{
    protected $platform;
    
    /**
     *
     * */
    function __construct(string $platform,array $data){
        switch ($platform){
            case 'huobi':{
                $this->platform=new Huobi($data);
                break;
            }
            case 'bitmex':{
                $this->platform=new Bitmex($data);
                break;
            }
            case 'okex':{
                $this->platform=new Okex($data);
                break;
            }
            case 'binance':{
                $this->platform=new Binance($data);
                break;
            }
        }
    }
}