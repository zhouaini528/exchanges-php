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
     * 初始化交易所
     * */
    function __construct(string $platform,string $key,string $secret,string $extra='',string $host=''){
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
        }
    }
}