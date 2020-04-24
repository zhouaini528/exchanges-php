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
            /* 'all'=>'NEW',
            'open'=>'NEW',
            'ordering'=>'NEW',
            'filled'=>'FILLED',
            'part_filled'=>'PART_FILLED',
            'canceling'=>'CANCELING',
            'cancelled'=>'CANCELLED',
            'failure'=>'FAILURE', */
            
            //订单状态("-2":失败,"-1":撤单成功,"0":等待成交 ,"1":部分成交, "2":完全成交,"3":下单中,"4":撤单中,）
            '-2'=>'FAILURE',
            '-1'=>'CANCELLED',
            '0'=>'NEW',
            '1'=>'PART_FILLED',
            '2'=>'FILLED',
            '3'=>'NEW',
            '4'=>'CANCELING',
        ],
        'future'=>[
            //订单状态("-2":失败,"-1":撤单成功,"0":等待成交 ,"1":部分成交, "2":完全成交,"3":下单中,"4":撤单中,）
            '-2'=>'FAILURE',
            '-1'=>'CANCELLED',
            '0'=>'NEW',
            '1'=>'PART_FILLED',
            '2'=>'FILLED',
            '3'=>'NEW',
            '4'=>'CANCELING',
        ],
        'swap'=>[
            //	订单状态("-2":失败,"-1":撤单成功,"0":等待成交 ,"1":部分成交, "2":完全成交,"3":下单中,"4":撤单中,）
            '-2'=>'FAILURE',
            '-1'=>'CANCELLED',
            '0'=>'NEW',
            '1'=>'PART_FILLED',
            '2'=>'FILLED',
            '3'=>'NEW',
            '4'=>'CANCELING',
        ]
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
        'swap'=>[
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
                switch ($this->checkType($data['request']['contract_code'] ?? ($data['request']['_symbol'] ?? ''))){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'swap':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'spot':{
                        $map['_order_id']=$data['result']['data'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['clientOrderId'] ?? '');
                        break;
                    }
                }
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_filed_amount']=bcmul(strval($data['result']['cumQty']),strval($data['result']['avgPx']),16);
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
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';

                $map['_symbol']=$data['result']['symbol'] ?? '';
                break;
            }
            case 'kucoin':{
                $map['_order_id']=$data['result']['data']['orderId'] ?? '';
                if(isset($data['result']['code']) && $data['result']['code']!=200000) $map['_status']='FAILURE';
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
                switch ($this->checkType($data['request']['contract_code'] ?? ($data['request']['_symbol'] ?? ''))){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'swap':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'spot':{
                        $map['_order_id']=$data['result']['data'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['clientOrderId'] ?? '');
                        break;
                    }
                }
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_filed_amount']=bcmul(strval($data['result']['cumQty']),strval($data['result']['avgPx']),16);
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
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';

                $map['_symbol']=$data['result']['symbol'] ?? '';
                break;
            }
            case 'kucoin':{
                $map['_order_id']=$data['result']['data']['orderId'] ?? '';
                if(isset($data['result']['code']) && $data['result']['code']!=200000) $map['_status']='FAILURE';
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
                $type=$this->checkType($data['request']['_symbol'] ?? '');
                switch ($type){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['successes'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['client_order_id'] ?? '');
                        break;
                    }
                    case 'swap':{
                        $map['_order_id']=$data['result']['data']['successes'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['client_order_id'] ?? '');
                        break;
                    }
                    case 'spot':{
                        $map['_order_id']=$data['result']['data'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['clientOrderId'] ?? '');
                        break;
                    }
                }
                
                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
                
                //合约交易撤单成功，再次撤相同ID单号会报1071
                if(isset($data['result']['data']['errors'][0]['err_code']) && $data['result']['data']['errors'][0]['err_code']==1071) {
                    $map['_status']='CANCELLED';
                    $map['_order_id']=$data['result']['data']['errors'][0]['order_id'];
                }
                    
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
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                $map['_status']=$this->binance_status[$data['result']['status']];

                break;
            }
            case 'kucoin':{
                $map['_order_id']=$data['request']['_order_id'] ?? '';
                
                if(isset($data['result']['code']) && $data['result']['code']!=200000) $map['_status']='FAILURE';
                if(isset($data['result']['data']['totalNum'])) $map['_status']='FAILURE';
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
        if(empty($data['result'])) return array_merge($data['result'],['_status'=>'FAILURE','msg'=>'Something went wrong last time']);;
        
        $map=[];
        switch ($this->platform){
            case 'huobi':{
                
                //判断是期货还是现货
                switch ($this->checkType($data['request']['_symbol'] ?? '')){
                    case 'spot':{
                        $map['_order_id']=$data['result']['data']['id'];
                        $map['_filled_qty']=$data['result']['data']['field-amount'];
                        $data['result']['data']['field-amount'] == 0 ? $map['_price_avg']=0:$map['_price_avg']=bcdiv(strval($data['result']['data']['field-cash-amount']),strval($data['result']['data']['field-amount']),16);
                        $map['_status']=$this->huobi_status['spot'][$data['result']['data']['state']];
                        $map['_filed_amount']=$data['result']['data']['field-cash-amount'];
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['clientOrderId'] ?? '');
                        break;
                    }
                    case 'future':{
                        $map['_order_id']=$data['result']['data'][0]['order_id'];
                        $map['_client_id']=$data['result']['data'][0]['client_order_id'];
                        $map['_filled_qty']=$data['result']['data'][0]['trade_volume'];
                        $map['_price_avg']=$data['result']['data'][0]['trade_avg_price'];
                        $map['_filed_amount']=$data['result']['data'][0]['trade_turnover'];
                        $map['_status']=$this->huobi_status['future'][$data['result']['data'][0]['status']];
                        break;
                    }
                    case 'swap':{
                        $map['_order_id']=$data['result']['data'][0]['order_id'];
                        $map['_client_id']=$data['result']['data'][0]['client_order_id'];
                        $map['_filled_qty']=$data['result']['data'][0]['trade_volume'];
                        $map['_price_avg']=$data['result']['data'][0]['trade_avg_price'];
                        $map['_filed_amount']=$data['result']['data'][0]['trade_turnover'];
                        $map['_status']=$this->huobi_status['swap'][$data['result']['data'][0]['status']];
                        break;
                    }
                }

                if(!isset($data['result']['status']) || $data['result']['status']!='ok') $map['_status']='FAILURE';
                break;
            }
            case 'bitmex':{
                $map['_order_id']=$data['result']['orderID'];
                $map['_client_id']=$data['result']['clOrdID'];
                $map['_filled_qty']=$data['result']['cumQty'];
                $map['_price_avg']=$data['result']['avgPx'];
                $map['_filed_amount']=bcmul(strval($data['result']['cumQty']),strval($data['result']['avgPx']),16);
                $map['_status']=$this->bitmex_status[$data['result']['ordStatus']];
                break;
            }
            case 'okex':{
                $map['_order_id']=$data['result']['order_id'];
                $map['_client_id']=$data['result']['client_oid'];

                //判断是期货还是现货
                switch ($this->checkType($data['result']['instrument_id'])){
                    case 'spot':{
                        //okex 小币种 精度又丢失的情况  不如dash-usdt  filled_notional:只精度到0.1位  所以采用倒推的方式  filled_notional=price_avg*filled_size 
                        $map['_filed_amount']=bcmul(strval($data['result']['price_avg']),strval($data['result']['filled_size']),16);
                        
                        $map['_filled_qty']=$data['result']['filled_size'];
                        $data['result']['filled_size']==0 ? $map['_price_avg']=0 : $map['_price_avg']=bcdiv(strval($map['_filed_amount']),strval($data['result']['filled_size']),16);
                        $map['_status']=$this->okex_status['spot'][$data['result']['state']];
                        break;
                    }
                    case 'future':{
                        $map['_filled_qty']=$data['result']['filled_qty'];
                        $map['_price_avg']=$data['result']['price_avg'];
                        $map['_filed_amount']=bcmul(strval($data['result']['filled_qty']),strval($data['result']['price_avg']),16);
                        $map['_status']=$this->okex_status['future'][$data['result']['state']];
                        break;
                    }
                    case 'swap':{
                        $map['_filled_qty']=$data['result']['filled_qty'];
                        $map['_price_avg']=$data['result']['price_avg'];
                        $map['_filed_amount']=bcmul(strval($data['result']['filled_qty']),strval($data['result']['price_avg']),16);
                        $map['_status']=$this->okex_status['swap'][$data['result']['state']];
                        break;
                    }
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                $map['_filled_qty']=$data['result']['executedQty'];
                $map['_status']=$this->binance_status[$data['result']['status']];
                
                switch ($this->checkType()){
                    case 'future':{
                        $map['_price_avg']=$data['result']['avgPrice'];
                        $map['_filed_amount']=bcmul(strval($data['result']['executedQty']),strval($data['result']['avgPrice']),16);
                        break;
                    }
                    case 'spot':{
                        $data['result']['executedQty']==0 ? $map['_price_avg']=0 : $map['_price_avg']=bcdiv(strval($data['result']['cummulativeQuoteQty']),strval($data['result']['executedQty']),16);
                        $map['_filed_amount']=$data['result']['cummulativeQuoteQty'];
                        break;
                    }
                }
                break;
            }
            case 'kucoin':{
                $map['_order_id']=$data['result']['data']['id'] ?? '';
                $map['_client_id']=$data['result']['data']['clientOid'] ?? '';
                if(isset($data['result']['code']) && $data['result']['code']!=200000) $map['_status']='FAILURE';
                if(isset($data['result']['data']['totalNum'])) $map['_status']='FAILURE';
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

