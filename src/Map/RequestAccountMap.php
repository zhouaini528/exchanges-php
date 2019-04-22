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
    
    /**
     *
     * */
    function get(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                
                //判断是期货还是现货
                if($this->checkFuture($map['symbol'])){
                    
                }else{
                    $map['account-id']=$data['account-id'] ?? $this->extra;
                }
                break;
            }
            case 'bitmex':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                break;
            }
            case 'okex':{
                $temp=$data['_symbol'] ?? ($data['instrument_id'] ?? $data['currency']);
                
                //判断是期货还是现货
                if($this->checkFuture($temp)){
                    $map['instrument_id']=$temp;
                }else{
                    $map['currency']=$temp;
                }
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;
        
        return $map;
    }
}


