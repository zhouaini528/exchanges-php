<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

use Lin\Exchange\Interfaces\TraderInterface;

/**
 * 交易接口参数映射
 * */
class ResponseTraderMap extends Base implements TraderInterface
{
    protected $bitmex_status=[
        'Filled'=>'FILLED',
        'New'=>'NEW',
        'PartiallyFilled'=>'PART_FILLED',
        'Canceled'=>'CANCELLED',
        'SystemError'=>'FAILURE',
    ];
    
    protected $okex_status=[
        'spot'=>[
            //订单状态(all:所有状态 open:未成交 part_filled:部分成交 canceling:撤销中 filled:已成交 cancelled:已撤销 ordering:下单中 failure：下单失败)
            'all'=>'NEW',
            'open'=>'NEW',
            'ordering'=>'NEW',
            'filled'=>'FILLED',
            'part_filled'=>'PART_FILLED',
            'canceling'=>'CANCELING',
            'cancelled'=>'CANCELLED',
            'failure'=>'FAILURE',
        ],
        'future'=>[
            //订单状态(-1.撤单成功；0:等待成交 1:部分成交 2:全部成交 ）
            '-1'=>'CANCELLED',
            '0'=>'NEW',
            '1'=>'PART_FILLED',
            '2'=>'FILLED',
        ],
    ];
    
    /**
     *  
     * */
    function sell(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_status']=$this->bitmex_status[$data['result']['ordStatus']];
                
                break;
            }
            case 'okex':{
                $map['_order_id']=$data['result']['order_id'] ?? '';
                $map['_client_id']=$data['result']['client_oid'] ?? '';
                
                if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';
                
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
    
    /**
     * 
     * @return [
     *      _status=>NEW 进行中   PART_FILLED 部分成交   FILLED 完全成交  CANCELING:撤销中   CANCELLED 已撤销   FAILURE 下单失败
     *      _filled_qty=>已交易完成数量
     *      _price_avg=>平均交易价格
     *      _order_id=>系统ID
     *      _client_id=>自定义ID
     * ]
     * */
    function buy(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_status']=$this->bitmex_status[$data['result']['ordStatus']];
                
                break;
            }
            case 'okex':{
                $map['_order_id']=$data['result']['order_id'] ?? '';
                $map['_client_id']=$data['result']['client_oid'] ?? '';
                
                if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';
                
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
    
    /**
     *
     * */
    function cancel(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_status']=$this->bitmex_status[$data['result']['ordStatus']];
                break;
            }
            case 'okex':{
                $map['_order_id']=$data['result']['order_id'] ?? '';
                $map['_client_id']=$data['result']['client_oid'] ?? '';
                
                if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
    
    /**
     *
     * */
    function update(array $data){
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
    function show(array $data){
        if(empty($data['result'])) return [];
        
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_status']=$this->bitmex_status[$data['result']['ordStatus']];
                break;
            }
            case 'okex':{
                $map['_order_id']=$data['result']['order_id'];
                $map['_client_id']=$data['result']['client_oid'];
                //判断是期货还是现货
                if(isset($data['request']['_future']) && $data['request']['_future']){
                    $map['_filled_qty']=$data['result']['filled_qty'];
                    $map['_price_avg']=$data['result']['price_avg'];
                    $map['_status']=$this->okex_status['future'][$data['result']['status']];
                }else{
                    $map['_filled_qty']=$data['result']['filled_size'];
                    $map['_price_avg']=$data['result']['filled_notional'];
                    $map['_status']=$this->okex_status['spot'][$data['result']['status']];
                }
                break;
            }
            case 'binance':{
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
    
    /**
     *
     * */
    function showAll(array $data){
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

