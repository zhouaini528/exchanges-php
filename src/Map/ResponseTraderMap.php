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
        'PendingCancel'=>'CANCELING',
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
        ],
        'v5'=>[
            'failure'=>'FAILURE',
            'canceled'=>'CANCELLED',
            'live'=>'NEW',
            'partially_filled'=>'PART_FILLED',
            'filled'=>'FILLED',
        ]
    ];

    protected $huobi_status=[
        'spot'=>[
            //submitting , submitted 已提交, partial-filled 部分成交, partial-canceled 部分成交撤销, filled 完全成交, canceled 已撤销
            'submitting'=>'NEW',
            'submitted'=>'NEW',
            'filled'=>'FILLED',
            'partial-filled'=>'PART_FILLED',
            'partial-canceled'=>'CANCELLED',
            'canceled'=>'CANCELLED',
        ],
        'future'=>[
            //(1准备提交 2准备提交 3已提交 4部分成交 5部分成交已撤单 6全部成交 7已撤单 11撤单中)
            '1'=>'NEW',
            '2'=>'NEW',
            '3'=>'NEW',
            '6'=>'FILLED',
            '4'=>'PART_FILLED',
            '5'=>'CANCELLED',
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
            '5'=>'CANCELLED',
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

    protected $bybit_status=[
        /*
            活動態
            New 訂單成功下達
            PartiallyFilled 部分成交
            Untriggered 條件單未觸發
            終態

            Rejected 訂單被拒絕
            PartiallyFilledCanceled 僅現貨存在該枚舉值, 訂單部分成交且已取消
            Filled 完全成交
            Cancelled 期貨交易，當訂單是該狀態時，是可能存在部分成交的; 經典帳戶的現貨盈止損單、條件單、OCO訂單觸發前取消
            Triggered 已觸發, 條件單從未觸發到變成New的一個中間態
            Deactivated 統一帳戶下期貨、現貨的盈止損單、條件單、OCO訂單觸發前取消
        */
        'New'=>'NEW',
        'PartiallyFilled'=>'PART_FILLED',
        'Cancelled'=>'CANCELLED',
        'Rejected'=>'Rejected',

        'Filled'=>'FILLED',
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
        switch ($this->exchange){
            case 'huobi':{
                switch ($this->checkType($data['request']['contract_code'] ?? ($data['request']['_symbol'] ?? ''))){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'linear':
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
                if($this->version=='v5'){
                    $map['_order_id']=$data['result']['data'][0]['ordId'] ?? '';
                    $map['_client_id']=$data['result']['data'][0]['clOrdId'] ?? '';

                    if(isset($data['result']['data'][0]) && $data['result']['data'][0]['sCode']!=0) $map['_status']='FAILURE';
                    if(isset($data['result']['code']) && $data['result']['code']!=0) $map['_status']='FAILURE';

                    $map['_symbol']=$data['request']['_symbol'];
                }else{
                    $map['_order_id']=$data['result']['order_id'] ?? '';
                    $map['_client_id']=$data['result']['client_oid'] ?? '';

                    if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                    if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';

                    //目的支持原生
                    if(isset($data['request']['instrument_id'])) {
                        $map['_symbol']=$data['request']['instrument_id'];
                    }
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
            case 'bybit':{
                if(!isset($data['result']['retCode']) || $data['result']['retCode']!='0') {
                    $map['_status']='FAILURE';
                    $map=array_merge($map,$data);
                    break;
                }

                $map['_order_id']=$data['result']['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['result']['orderLinkId'] ?? '';
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

        switch ($this->exchange){
            case 'huobi':{
                switch ($this->checkType($data['request']['contract_code'] ?? ($data['request']['_symbol'] ?? ''))){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['order_id'] ?? '';
                        $map['_symbol']=$data['request']['_symbol'] ?? $data['request']['contract_code'];
                        break;
                    }
                    case 'linear':
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
                if($this->version=='v5'){
                    $map['_order_id']=$data['result']['data'][0]['ordId'] ?? '';
                    $map['_client_id']=$data['result']['data'][0]['clOrdId'] ?? '';

                    if(isset($data['result']['data'][0]) && $data['result']['data'][0]['sCode']!=0) $map['_status']='FAILURE';
                    if(isset($data['result']['code']) && $data['result']['code']!=0) $map['_status']='FAILURE';

                    $map['_symbol']=$data['request']['_symbol'];
                }else{
                    $map['_order_id']=$data['result']['order_id'] ?? '';
                    $map['_client_id']=$data['result']['client_oid'] ?? '';

                    if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                    if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';

                    //目的支持原生
                    if(isset($data['request']['instrument_id'])) {
                        $map['_symbol']=$data['request']['instrument_id'];
                    }
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
            case 'bybit':{
                if(!isset($data['result']['retCode']) || $data['result']['retCode']!='0') {
                    $map['_status']='FAILURE';
                    $map=array_merge($map,$data);
                    break;
                }

                $map['_order_id']=$data['result']['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['result']['orderLinkId'] ?? '';
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

        switch ($this->exchange){
            case 'huobi':{
                $type=$this->checkType($data['request']['_symbol'] ?? '');
                switch ($type){
                    case 'future':{
                        $map['_order_id']=$data['result']['data']['successes'] ?? '';
                        $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['client_order_id'] ?? '');
                        break;
                    }
                    case 'linear':
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
                if($this->version=='v5'){
                    $map=$data['request'];

                    if($data['result']['code']==1 && $data['result']['data'][0]['sCode']==51401) $map['_status']=$this->okex_status['v5']['canceled'];
                }else{
                    $map['_order_id']=$data['result']['order_id'] ?? '';
                    $map['_client_id']=$data['result']['client_oid'] ?? '';

                    if(isset($data['result']['error_code']) && !empty($data['result']['error_code'])) $map['_status']='FAILURE';
                    if(isset($data['result']['code']) && !empty($data['result']['code'])) $map['_status']='FAILURE';

                    //目的支持原生
                    if(isset($data['request']['instrument_id'])) {
                        $map['_symbol']=$data['request']['instrument_id'];
                    }
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
            case 'bybit':{
                $map['_order_id']=$data['result']['result']['order_id'] ?? '';
                $map['_client_id']=$data['result']['result']['orderLinkId'] ?? '';
                $map['_symbol']=$data['request']['_symbol'];
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

        switch ($this->exchange){
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
        switch ($this->exchange){
            case 'huobi':{
                //判断是期货还是现货
                switch ($this->checkType($data['request']['_symbol'] ?? '')){
                    case 'spot':{
                        $map['_order_id']=$data['result']['data']['id'];

                        $map['_filled_qty']=$data['result']['data']['field-amount'];
                        $map['_filed_amount']=$data['result']['data']['field-cash-amount'];

                        $map['_price_avg']=$data['result']['data']['field-amount'] == 0 ? 0 : bcdiv(strval($data['result']['data']['field-cash-amount']),strval($data['result']['data']['field-amount']),16);
                        $map['_status']=$this->huobi_status['spot'][$data['result']['data']['state']];

                        if(isset($data['result'])) $map['_client_id']=$data['result']['data']['client-order-id'] ?? '';
                        if(isset($data['request']) && empty($map['_client_id'])) $map['_client_id']=$data['request']['_client_id'] ?? ($data['request']['clientOrderId'] ?? '');

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
                    case 'linear':
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
                if($this->version=='v5'){
                    $map['_order_id']=$data['result']['data'][0]['ordId'] ?? '';
                    $map['_client_id']=$data['result']['data'][0]['clOrdId'] ?? $data['request']['_client_id'];
                    $map['_symbol']=$data['result']['data'][0]['instId'] ?? $data['request']['_symbol'];
                    if(!isset($data['result']['data'][0])) {
                        $map['_status']=$this->okex_status['v5']['failure'];
                        break;
                    }

                    $map['_filed_amount']=bcmul(strval($data['result']['data'][0]['accFillSz']),strval($data['result']['data'][0]['avgPx']),16);
                    $map['_filled_qty']=$data['result']['data'][0]['accFillSz'];
                    $map['_price_avg']=$data['result']['data'][0]['avgPx'];

                    $map['_status']=$this->okex_status['v5'][$data['result']['data'][0]['state']];
                }else{
                    $map['_order_id']=$data['result']['order_id'];
                    $map['_client_id']=$data['result']['client_oid'];

                    //判断是期货还是现货
                    switch ($this->checkType($data['result']['instrument_id'])){
                        case 'spot':{
                            $map['_filed_amount']=$data['result']['filled_notional'];
                            $map['_filled_qty']=$data['result']['filled_size'];
                            $map['_price_avg']=$data['result']['price_avg'];
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
                }
                break;
            }
            case 'binance':{
                $map['_order_id']=$data['result']['orderId'] ?? '';
                $map['_client_id']=$data['result']['clientOrderId'] ?? '';
                $map['_filled_qty']=$data['result']['executedQty'];
                $map['_status']=$this->binance_status[$data['result']['status']];

                switch ($this->checkType($data['result']['symbol'])){
                    case 'swap':
                    case 'delivery':
                    case 'future':{
                        $map['_price_avg']=isset($data['result']['avgPrice']) ? $data['result']['avgPrice'] : 0;
                        break;
                    }
                    case 'spot':{
                        $map['_price_avg']=$data['result']['executedQty']==0 ? 0 : bcdiv(strval($data['result']['cummulativeQuoteQty']),strval($data['result']['executedQty']),16);
                        $map['_filed_amount']=$data['result']['cummulativeQuoteQty'];
                        break;
                    }
                    default:{
                        $map['_price_avg']=isset($data['result']['avgPrice']) ? $data['result']['avgPrice'] : 0;
                        $map['_filed_amount']=bcmul(strval($data['result']['executedQty']),strval($data['result']['avgPrice']),16);
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
            case 'bybit':{
                $map['_order_id']=$data['result']['result']['list'][0]['orderId'] ?? '';
                $map['_client_id']=$data['result']['result']['list'][0]['orderLinkId'] ?? '';
                $map['_symbol']=$data['result']['result']['list'][0]['symbol'] ?? '';

                /*cumExecQty string 訂單累計成交數量
                > cumExecValue string 訂單累計成交價值. 經典帳戶現貨交易不支持
                > cumExecFee string 已棄用. 訂單累計成交的手續費. 經典帳戶現貨交易不支持*/
                $map['_filled_qty']=$data['result']['result']['list'][0]['cumExecQty'];
                $map['_price_avg']=$data['result']['result']['list'][0]['avgPrice'];
                $map['_filed_amount']=$data['result']['result']['list'][0]['cumExecValue'];

                $map['_status']=$this->bybit_status[$data['result']['result']['list'][0]['orderStatus']];
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

        switch ($this->exchange){
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

