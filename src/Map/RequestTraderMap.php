<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Map;

use Lin\Exchange\Interfaces\TraderInterface;

/**
 * 交易接口参数映射
 * */
class RequestTraderMap extends Base implements TraderInterface
{
    /**
     *
     * */
    function buy(array $data){
        $map=[];

        switch ($this->exchange){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];

                if(isset($data['contract_code'])) $map['symbol']=$data['contract_code'];

                switch ($this->checkType($map['symbol'])){
                    case 'future':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='buy';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;

                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                    case 'linear':
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='buy';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 5;

                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                    case 'spot':{
                        $map['account-id']=$data['account-id'] ?? $this->extra;
                        $map['client-order-id']=$data['_client_id'] ?? ($data['client-order-id'] ?? '');

                        if(isset($data['_number']) && isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['type']=$data['type'] ?? 'buy-limit';
                            $map['amount']=$data['_number'] ?? $data['amount'];
                        }else {
                            $map['type']=$data['type'] ?? 'buy-market';
                            $map['amount']=$data['_price'] ?? $data['amount'];//市价买单时表示买多少钱
                        }
                        break;
                    }
                }
                break;
            }
            case 'bitmex':{
                $map['clOrdID']=$data['_client_id'] ?? ($data['clOrdID'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['orderQty']=$data['_number'] ?? $data['orderQty'];
                $map['side']='Buy';

                if(isset($data['_number']) && isset($data['_price'])){
                    $map['price']=$data['_price'];
                    $map['ordType']=$data['ordType'] ?? 'Limit';
                }else{
                    $map['ordType']=$data['ordType'] ?? 'Market';
                }

                if(isset($data['_entry'])){
                    $map['side'] = $data['_entry'] ? 'Buy':'Sell' ;
                }

                //支持原生参数
                $data['side']=$map['side'];
                break;
            }
            case 'okex':{
                if($this->version=='v5'){
                    //v5
                    $map['clOrdId']=$data['_client_id'] ?? ($data['clOrdId'] ?? '');
                    $map['instId']=$data['_symbol'] ?? $data['instId'];
                    $map['side']='buy';

                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['ordType']='limit';
                        $map['px']=$data['_price'];
                        $map['sz']=$data['_number'] ?? 0;
                    }else{
                        $map['ordType']='market';
                    }

                    switch ($this->platform){
                        case 'margin':{
                            break;
                        }
                        case 'future':
                        case 'swap':{
                            $map['tdMode']=$data['tdMode'] ?? 'cross';
                            $map['posSide']='long';
                            $map['sz']=$data['_number'] ?? 0;

                            //平多
                            if(!$data['_entry']) $map['side']='sell';

                            break;
                        }
                        case 'spot':
                        default:{//spot
                            $map['tdMode']=$data['tdMode'] ?? 'cash';

                            if(isset($data['_number']) && $data['_number']>0 && $map['ordType']=='market'){
                                $map['tgtCcy']='base_ccy';
                                $map['sz']=$data['_number'];
                            }

                            if(isset($data['_price']) && $data['_price']>0 && $map['ordType']=='market'){
                                $map['tgtCcy']='quote_ccy';
                                $map['sz']=$data['_price'];
                            }
                        }
                    }
                }else{
                    //v3
                    $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                    $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];

                    switch ($this->checkType($map['instrument_id'])){
                        case 'spot':{
                            $data['side']=$map['side']='buy';
                            $map['margin_trading']=1;
                            $map['order_type']=$data['order_type'] ?? 0;

                            //市价单与限价单的参数映射
                            if(isset($data['_number']) && isset($data['_price'])){
                                $map['type']='limit';
                                $map['price']=$data['_price'] ?? $data['price'];
                                $map['size']=$data['_number'] ?? $data['size'];
                            }else{
                                $map['type']='market';
                                if(isset($data['_number'])){
                                    $map['size']=$data['_number'] ?? $data['size'];
                                }

                                if(isset($data['_price'])){
                                    $map['notional']=$data['_price'] ?? $data['notional'];
                                }
                            }
                            break;
                        }
                        case 'swap':{

                        }
                        case 'future':{
                            //	1:开多2:开空3:平多4:平空
                            $map['type']=$data['type'] ?? ($data['_entry']?1:4);

                            //市价单与限价单的参数映射
                            if(isset($data['_number']) && isset($data['_price'])){
                                $map['price']=$data['_price'];
                                $map['size']=$data['_number'] ?? 0;

                                $map['order_type']=$data['order_type'] ?? 0;
                            }else{
                                $map['size']=$data['_number'] ?? 0;

                                $map['order_type']=$data['order_type'] ?? 4;
                            }

                            $map['leverage']=$data['leverage'] ?? 10;
                            break;
                        }
                    }
                }

                $this->checkOrderType($map['ordType']);
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['side']='BUY';

                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);

                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                    $map['price']=$data['_price'] ?? ($data['quoteOrderQty'] ?? '');
                    $map['type']='LIMIT';

                    /*$data['_entry']=null;
                    print_r($data);
                    var_dump($data['_entry'] ?? '');die;*/

                    switch ($this->checkType(isset($data['_entry']) ?? '')){
                        case 'swap':
                        case 'delivery':
                        case 'future':{
                            //LONG多     SHORT空
                            //开多 buy long     平多 sell long
                            //平空 buy short     开空 sell short
                            if($data['_entry']) $map['side']='BUY';
                            else $map['side']='SELL';

                            $map['positionSide']='LONG';
                            break;
                        }
                        default:{
                        }
                    }
                }else{
                    //quantity 的市价单
                    //MARKET 明确的是用户想用市价单买入或卖出的数量。
                    //BTCUSDT上下一个市价单,quantity用户指明能够买进或者卖出多少BTC。

                    //quoteOrderQty 的市价单
                    //MARKET 明确的是通过买入(或卖出)想要花费(或获取)的报价资产数量; 此时的正确报单数量将会以市场流动性和
                    //quoteOrderQty被计算出来。

                    //BTCUSDT为例,
                    //quoteOrderQty=100:
                    //下买单的时候, 订单会尽可能的买进价值100USDT的BTC.下卖单的时候, 订单会尽可能的卖出价值100USDT的BTC.

                    //可以理解为以BTCUSDT  quantity处理BTC(交易币)   quoteOrderQty处理USDT(计价币)
                    //TODO

                    if(isset($data['_price'])){
                        $map['quoteOrderQty']=$data['_price'] ?? ($data['quoteOrderQty'] ?? '');
                    }

                    if(isset($data['_number'])){
                        $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                    }

                    switch ($this->checkType(isset($data['_entry']) ?? '')){
                        case 'swap':
                        case 'delivery':
                        case 'future':{
                            //LONG多     SHORT空
                            //开多 buy long     平多 sell long
                            //平空 buy short     开空 sell short
                            if($data['_entry']) $map['side']='BUY';
                            else $map['side']='SELL';

                            $map['positionSide']='LONG';
                            break;
                        }
                        case 'spot':{
                            $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                            break;
                        }
                        default:{
                            $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                        }
                    }
                    $map['type']='MARKET';
                }

                //支持原生参数
                $data['side']=$map['side'];

                $this->checkOrderType($map['type']);
                break;
            }
            case 'kucoin':{
                $map['clientOid']=$data['_client_id'] ?? $data['clientOid'];
                $map['side']='buy';
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];


                switch ($this->checkType()){
                    case 'future':{
                        $map['leverage']=$data['leverage'] ?? 20;
                        //市价单与限价单的参数映射
                        if(isset($data['_number']) && isset($data['_price'])){
                            $map['price']=$data['_price'] ?? $data['price'];
                            $map['size']=$data['_number'] ?? $data['size'];
                            $map['type']='limit';
                        }else{
                            $map['size']=$data['_number'] ?? $data['size'];
                            $map['type']='market';
                        }
                        break;
                    }
                    case 'spot':{
                        //市价单与限价单的参数映射
                        if(isset($data['_number']) && isset($data['_price'])){
                            $map['price']=$data['_price'] ?? $data['price'];
                            $map['size']=$data['_number'] ?? $data['size'];
                            $map['type']='limit';
                        }else{
                            if(isset($data['_number'])) $map['size']=$data['_number'];
                            if(isset($data['_price'])) $map['funds']=$data['_price'];
                            $map['type']='market';
                        }
                        break;
                    }
                }

                //支持原生参数
                $data['side']=$map['side'];
                break;
            }
            case 'bybit':{
                $map['orderLinkId']=$data['_client_id'] ?? ($data['orderLinkId'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['category']=$this->platform;
                $map['side']='buy';

                switch ($this->platform){
                    case 'linear':
                    case 'inverse':{

                        break;
                    }
                    case 'spot':
                    default:{//spot


                        //市价单与限价单的参数映射
                        if(isset($data['_number']) && isset($data['_price'])){
                            $map['orderType']='limit';
                            $map['qty']=$data['_number'];
                            $map['price']=$data['_price'];
                        }else{
                            $map['orderType']='market';
                            //市价买卖qty 对应单位不一样
                            /*
                             * 統一帳戶
                            現貨: 可以通過設置marketUnit來表示市價單qty的單位, 市價買單默認是quoteCoin, 市價賣單默認是baseCoin
                            期貨和期權: 總是以base coin作為qty的單位

                            marketUnit false string 統一帳戶現貨交易創建市價單時給入參qty指定的單位, 支持orderFilter=Order, tpslOrder 和 StopOrder
                            baseCoin: 比如, 買BTCUSDT, 則"qty"的單位是BTC
                            quoteCoin: 比如, 賣BTCUSDT, 則"qty"的單位是USDT
                            */
                            if(isset($data['_number'])){
                                $map['qty']=$data['_number'];
                                $map['marketUnit']='baseCoin';
                            }

                            if(isset($data['_price'])){
                                $map['qty']=$data['_price'];
                                $map['marketUnit']='quoteCoin';
                            }
                        }
                    }
                }
                break;
            }
        }

        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;

        return $map;
    }

    /**
     *
     * */
    function sell(array $data){
        $map=[];

        switch ($this->exchange){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];

                if(isset($data['contract_code'])) $map['symbol']=$data['contract_code'];

                switch ($this->checkType($map['symbol'])){
                    case 'spot':{
                        $map['account-id']=$data['account-id'] ?? $this->extra;
                        $map['client-order-id']=$data['_client_id'] ?? ($data['client-order-id'] ?? '');

                        if(isset($data['_number']) && isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['type']=$data['type'] ?? 'sell-limit';
                            $map['amount']=$data['_number'] ?? $data['amount'];
                        }else {
                            $map['type']=$data['type'] ?? 'sell-market';
                            $map['amount']=$data['_number'] ?? $data['amount'];//市价卖单时表示卖多少币
                        }
                        break;
                    }
                    case 'future':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='sell';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;

                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                    case 'linear':
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='sell';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;

                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                }
                break;
            }
            case 'bitmex':{
                $map['clOrdID']=$data['_client_id'] ?? ($data['clOrdID'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['orderQty']=$data['_number'] ?? $data['orderQty'];
                $map['side']='Sell';

                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['price']=$data['_price'];
                    $map['ordType']=$data['ordType'] ?? 'Limit';
                }else{
                    $map['ordType']=$data['ordType'] ?? 'Market';
                }

                if(isset($data['_entry'])){
                    $map['side'] = $data['_entry'] ? 'Sell':'Buy' ;
                }

                //支持原生参数
                $data['side']=$map['side'];

                break;
            }
            case 'okex':{
                if($this->version=='v5'){
                    //v5
                    $map['clOrdId']=$data['_client_id'] ?? ($data['clOrdId'] ?? '');
                    $map['instId']=$data['_symbol'] ?? $data['instId'];
                    $map['side']='sell';

                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['ordType']='limit';
                        $map['px']=$data['_price'];
                        $map['sz']=$data['_number'] ?? 0;
                    }else{
                        $map['ordType']='market';
                    }
                    switch ($this->platform){
                        case 'margin':{
                            break;
                        }
                        case 'future':
                        case 'swap':{
                            $map['tdMode']=$data['tdMode'] ?? 'cross';
                            $map['posSide']='short';
                            $map['sz']=$data['_number'] ?? 0;
                            //平空
                            if(!$data['_entry']) $map['side']='buy';

                            break;
                        }
                        case 'spot':
                        default:{//spot
                            $map['tdMode']=$data['tdMode'] ?? 'cash';

                            if(isset($data['_number']) && $data['_number']>0 && $map['ordType']=='market'){
                                $map['tgtCcy']='base_ccy';
                                $map['sz']=$data['_number'];
                            }

                            if(isset($data['_price']) && $data['_price']>0 && $map['ordType']=='market'){
                                $map['tgtCcy']='quote_ccy';
                                $map['sz']=$data['_price'];
                            }
                        }
                    }
                }else{
                    $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                    $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];

                    switch ($this->checkType($map['instrument_id'])){
                        case 'spot':{
                            $data['side']=$map['side']='sell';
                            $map['margin_trading']=1;
                            $map['order_type']=$data['order_type'] ?? 0;

                            //市价单与限价单的参数映射
                            if(isset($data['_number']) && isset($data['_price'])){
                                $map['type']='limit';
                                $map['price']=$data['_price'] ?? $data['price'];
                                $map['size']=$data['_number'] ?? $data['size'];
                            }else{
                                $map['type']='market';
                                if(isset($data['_number'])){
                                    $map['size']=$data['_number'] ?? $data['size'];
                                }

                                if(isset($data['_price'])){
                                    $map['notional']=$data['_price'] ?? $data['notional'];
                                }
                            }
                            break;
                        }
                        case 'swap':{
                        }
                        case 'future':{
                            $map['type']=$data['type'] ?? ($data['_entry']?2:3);

                            //市价单与限价单的参数映射
                            if(isset($data['_number']) && isset($data['_price'])){
                                $map['price']=$data['_price'];
                                $map['size']=$data['_number'] ?? 0;
                                $map['order_type']=$data['order_type'] ?? 0;
                            }else{
                                $map['size']=$data['_number'] ?? 0;
                                $map['order_type']=$data['order_type'] ?? 4;
                            }

                            //判断是否是交割合约
                            if(!stripos($map['instrument_id'],'SWAP')){
                                $map['leverage']=$data['leverage'] ?? 10;
                            }
                            break;
                        }
                    }
                }

                $this->checkOrderType($map['ordType']);
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['side']='SELL';

                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);

                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                    $map['price']=$data['_price'] ?? ($data['quoteOrderQty'] ?? '');
                    $map['type']='LIMIT';

                    switch ($this->checkType(isset($data['_entry']) ?? '')){
                        case 'swap':
                        case 'delivery':
                        case 'future':{
                            //LONG多     SHORT空
                            //开多 buy long     平多 sell long
                            //平空 buy short     开空 sell short
                            if($data['_entry']) $map['side']='SELL';
                            else $map['side']='BUY';

                            $map['positionSide']='SHORT';
                            break;
                        }
                        default:{
                        }
                    }
                }else{
                    if(isset($data['_price'])){
                        $map['quoteOrderQty']=$data['_price'] ?? ($data['quoteOrderQty'] ?? '');
                    }

                    if(isset($data['_number'])){
                        $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                    }

                    switch ($this->checkType(isset($data['_entry']) ?? '')){
                        case 'swap':
                        case 'delivery':
                        case 'future':{
                            if($data['_entry']) $map['side']='SELL';
                            else $map['side']='BUY';

                            $map['positionSide']='SHORT';
                            break;
                        }
                        case 'spot':{
                            $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                            break;
                        }
                        default:{
                            $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                        }
                    }
                    $map['type']='MARKET';
                }

                //支持原生参数
                $data['side']=$map['side'];

                $this->checkOrderType($map['type']);
                break;
            }
            case 'kucoin':{
                $map['clientOid']=$data['_client_id'] ?? $data['clientOid'];
                $map['side']='sell';
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];

                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['price']=$data['_price'] ?? $data['price'];
                    $map['size']=$data['_number'] ?? $data['size'];
                    $map['type']='limit';
                }else{
                    if(isset($data['_number'])) $map['size']=$data['_number'];
                    if(isset($data['_price'])) $map['funds']=$data['_price'];
                    $map['type']='market';
                }

                //支持原生参数
                $data['side']=$map['side'];
                break;
            }
        }

        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;

        return $map;
    }

    /**
     *
     * */
    function cancel(array $data){
        $map=[];
        switch ($this->exchange){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? ($data['symbol'] ?? '');

                if(isset($data['contract_code'])) $map['symbol']=$data['contract_code'];

                switch ($this->checkType($map['symbol'])){
                    case 'future':{
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');

                        if(empty($map['order_id'])) unset($map['order_id']);
                        if(empty($map['client_order_id'])) unset($map['client_order_id']);
                        break;
                    }
                    case 'linear':
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');

                        if(empty($map['order_id'])) unset($map['order_id']);
                        if(empty($map['client_order_id'])) unset($map['client_order_id']);
                        unset($map['symbol']);
                        break;
                    }
                    case 'spot':
                    default:{
                        $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');

                        $map['client-order-id']=$data['_client_id'] ?? ($data['client-order-id'] ?? '');
                        if(empty($map['client-order-id'])) unset($map['client-order-id']);

                        unset($map['symbol']);
                    }
                }
                break;
            }
            case 'bitmex':{
                $map['orderID']=$data['_order_id'] ?? ($data['orderID'] ?? '');
                $map['clOrdID']=$data['_client_id'] ?? ($data['clOrdID'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                break;
            }
            case 'okex':{
                if($this->version=='v5'){
                    $map['ordId']=$data['_order_id'] ?? ($data['ordId'] ?? '');
                    $map['clOrdId']=$data['_client_id'] ?? ($data['clOrdId'] ?? '');
                    $map['instId']=$data['_symbol'] ?? $data['instId'];
                }else{
                    $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                    $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                    $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                    $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
                }

                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['orderId']=$data['_order_id'] ?? ($data['orderId'] ?? '');
                $map['origClientOrderId']=$data['_client_id'] ?? ($data['origClientOrderId'] ?? '');

                if(empty($map['orderId'])) unset($map['orderId']);
                if(empty($map['origClientOrderId'])) unset($map['origClientOrderId']);
                else $map['newClientOrderId']=$map['origClientOrderId'];


                break;
            }
            case 'kucoin':{
                switch ($this->checkType()){
                    case 'future':{
                        $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');
                        break;
                    }
                    case 'spot':{
                        $map['orderId']=$data['_order_id'] ?? ($data['orderId'] ?? '');
                        break;
                    }
                }
                break;
            }
        }

        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;

        return $map;
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

        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;

        return $map;
    }

    /**
     *
     * */
    function show(array $data){
        $map=[];

        switch ($this->exchange){
            case 'huobi':{
                switch ($this->checkType($data['_symbol'] ?? ($data['symbol'] ?? ($data['contract_code'] ?? '')))){
                    case 'future':{
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['symbol']=$data['_symbol'] ?? ($data['symbol'] ?? '');
                        break;
                    }
                    case 'linear':
                    case 'swap':{
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['contract_code']=$data['_symbol'] ?? ($data['contract_code'] ?? '');
                        break;
                    }
                    case 'spot':{
                        $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');

                        $map['clientOrderId']=$data['_client_id'] ?? ($data['clientOrderId'] ?? '');
                        if(empty($map['clientOrderId'])) unset($map['clientOrderId']);

                        break;
                    }
                }
                break;
            }
            case 'bitmex':{
                $map['orderID']=$data['_order_id'] ?? ($data['orderID'] ?? '');
                $map['clOrdID']=$data['_client_id'] ?? ($data['clOrdID'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];

                if(empty($map['orderID'])) unset($map['orderID']);
                if(empty($map['clOrdID'])) unset($map['clOrdID']);
                break;
            }
            case 'okex':{
                if($this->version=='v5'){
                    $map['ordId']=$data['_order_id'] ?? ($data['ordId'] ?? '');
                    $map['clOrdId']=$data['_client_id'] ?? ($data['clOrdId'] ?? '');
                    $map['instId']=$data['_symbol'] ?? $data['instId'];
                }else{
                    $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                    $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                    $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                    $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
                }
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['orderId']=$data['_order_id'] ?? ($data['orderId'] ?? '');
                $map['origClientOrderId']=$data['_client_id'] ?? ($data['origClientOrderId'] ?? '');

                if(empty($map['orderId'])) unset($map['orderId']);
                if(empty($map['origClientOrderId'])) unset($map['origClientOrderId']);

                break;
            }
            case 'kucoin':{
                switch ($this->checkType()){
                    case 'future':{
                        $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');
                        break;
                    }
                    case 'spot':{
                        $map['orderId']=$data['_order_id'] ?? ($data['orderId'] ?? '');
                        break;
                    }
                }
                break;
            }
        }

        //Detect whether the parameter is native
        if($this->checkOriginalParam($data)) return $data;

        return $map;
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

        //Detect whether the parameter is native
        if($this->checkOriginalParam($data)) return $data;

        return $map;
    }
}


