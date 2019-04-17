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
    
    protected $huobi_status=[
        'spot'=>[
            //submitting , submitted 已提交, partial-filled 部分成交, partial-canceled 部分成交撤销, filled 完全成交, canceled 已撤销
            'submitting'=>'NEW',
            'submitted'=>'NEW',
            'filled'=>'FILLED',
            'partial-filled'=>'PART_FILLED',
            'partial-canceled'=>'PART_FILLED',
            'canceled'=>'CANCELLED',
        ],
        'future'=>[
            //(1准备提交 2准备提交 3已提交 4部分成交 5部分成交已撤单 6全部成交 7已撤单 11撤单中)
            '1'=>'NEW',
            '2'=>'NEW',
            '3'=>'NEW',
            '6'=>'FILLED',
            '4'=>'PART_FILLED',
            '5'=>'PART_FILLED',
            '7'=>'CANCELLED',
            '8'=>'CANCELING',
        ],
    ];
    
    protected $binance_status=[
        //NEW 新建订单    PARTIALLY_FILLED 部分成交   FILLED 全部成交    CANCELED 已撤销    PENDING_CANCEL 正在撤销中(目前不会遇到这个状态)
        //REJECTED 订单被拒绝    EXPIRED 订单过期(根据timeInForce参数规则)
        'FILLED'=>'FILLED',
        'NEW'=>'NEW',
        'PARTIALLY_FILLED'=>'PART_FILLED',
        'CANCELED'=>'CANCELLED',
        
        'REJECTED'=>'FAILURE',
        'EXPIRED'=>'FAILURE',
    ];
    
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
                $map['_order_id']=$data['result']['data'] ?? '';
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
                
                //TODO 期货版本等待
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
                
                //目的支持原生
                if(isset($data['request']['instrument_id'])) {
                    $map['_symbol']=$data['request']['instrument_id'];
                    
                    $temp=explode('-', $map['_symbol']);
                    $map['_future']=count($temp)>2 ? true : false;
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                
                $map['_symbol']=$data['result']['symbol'] ?? '';
                break;
            }
        }
        
        return array_merge($data['result'],$map);
    }
    
    /**
     *  
     * */
    function sell(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                $map['_order_id']=$data['result']['data'] ?? '';
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
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
                
                //目的支持原生
                if(isset($data['request']['instrument_id'])) {
                    $map['_symbol']=$data['request']['instrument_id'];
                    
                    $temp=explode('-', $map['_symbol']);
                    $map['_future']=count($temp)>2 ? true : false;
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                
                $map['_symbol']=$data['result']['symbol'] ?? '';
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
                $map['_order_id']=$data['result']['data'] ?? '';
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
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
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                $map['_status']=$this->binance_status[$data['result']['status']];
                
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
                //判断是期货还是现货
                if(isset($data['request']['_future']) && $data['request']['_future']){
                    
                }else{
                    $map['_order_id']=$data['result']['data']['id'];
                    $map['_filled_qty']=$data['result']['data']['field-amount'];
                    $map['_price_avg']=$data['result']['data']['field-cash-amount'];
                    $map['_status']=$this->huobi_status['spot'][$data['result']['data']['state']];
                }
                
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
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
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                $map['_filled_qty']=$data['result']['executedQty'];
                $map['_price_avg']=$data['result']['cummulativeQuoteQty'];
                $map['_status']=$this->binance_status[$data['result']['status']];
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

