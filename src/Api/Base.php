<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Exchanges\Bybit;
use Lin\Exchange\Exchanges\Crex;
use Lin\Exchange\Map\Map;
use Lin\Exchange\Exchanges\Huobi;
use Lin\Exchange\Exchanges\Bitmex;
use Lin\Exchange\Exchanges\Okex;
use Lin\Exchange\Exchanges\Binance;
use Lin\Exchange\Exceptions\Exception;
use Lin\Exchange\Exchanges\Ku;
use Lin\Exchange\Exchanges\Bitfinex;
use Lin\Exchange\Exchanges\Mxc;
use Lin\Exchange\Exchanges\Coinbase;
use Lin\Exchange\Exchanges\Zb;
use Lin\Exchange\Exchanges\Bittrex;
use Lin\Exchange\Exchanges\Kraken;
use Lin\Exchange\Exchanges\Gate;


class Base
{
    protected $exchange;

    protected $map;

    protected $platform='';

    protected $version='';

    /**
     *
     * */
    function __construct(string $exchange,string $key,string $secret,string $extra='',string $host=''){
        $exchange=strtolower($exchange);

        switch ($exchange){
            case 'huobi':{
                $this->exchange=new Huobi($key,$secret,$host);
                break;
            }
            case 'bitmex':{
                $this->exchange=new Bitmex($key,$secret,$host);
                break;
            }
            case 'okex':{
                $this->exchange=new Okex($key,$secret,$extra,$host);
                break;
            }
            case 'binance':{
                $this->exchange=new Binance($key,$secret,$host);
                break;
            }
            case 'kumex':{}
            case 'kucoin':{
                $this->exchange=new Ku($key,$secret,$extra,$host);
                break;
            }
            case 'bitfinex':{
                $this->exchange=new Bitfinex($key,$secret,$host);
                break;
            }
            case 'mxc':{
                $this->exchange=new Mxc($key,$secret,$host);
                break;
            }
            case 'coinbase':{
                $this->exchange=new Coinbase($key,$secret,$extra,$host);
                break;
            }
            case 'zb':{
                $this->exchange=new Zb($key,$secret);
                break;
            }
            case 'bittrex':{
                $this->exchange=new Bittrex($key,$secret,$extra,$host);
                break;
            }
            case 'kraken':{
                $this->exchange=new Kraken($key,$secret,$host);
                break;
            }
            case 'gate':{
                $this->exchange=new Gate($key,$secret,$host);
                break;
            }
            case 'crex':
            case 'crex24':{
                $this->exchange=new Crex($key,$secret,$host);
                break;
            }
            case 'bybit':
            case 'bybitlinear':
            case 'bybitinverse':{
                $this->exchange=new Bybit($key,$secret,$host);
                break;
            }
            default:{
                throw new Exception("Exchanges don't exist");
            }
        }

        $this->map=new Map($exchange,$key,$secret,$extra,$host);
    }

    /**
     *
     * @param
     * @param string
     * @return array
     * */
    protected function error($msg,$status='FAILURE'){
        if(stripos($msg,'Connection timed out after')!==false){
            $httpcode=504;
        }

        $temp=json_decode($msg,true);
        if(!empty($temp) && is_array($temp)) {
            if(isset($httpcode)) $temp['_httpcode']=$httpcode;
            return ['_error'=>$temp,'_status'=>$status];
        }

        return ['_error'=>$msg,'_status'=>$status];
    }

    /**
     * Returns the underlying instance object
     * */
    function getPlatform(string $type=''){
        return $this->exchange->getPlatform($type);
    }

    /**
    Set exchange transaction category, default "spot" transaction. Other options "spot" "margin" "future" "swap"
     */
    public function setPlatform(string $platform=''){
        $this->exchange->setPlatform($platform);

        $this->map->setPlatform($platform);

        return $this;
    }

    /**
    Set exchange API interface version. for example "v1" "v3" "v5"
     */
    public function setVersion(string $version=''){
        $this->exchange->setVersion($version);

        $this->map->setVersion($version);

        return $this;
    }

    /**
     * Support for more request Settings
     * */
    function setOptions(array $options=[]){
        $this->exchange->setOptions($options);
    }
}
