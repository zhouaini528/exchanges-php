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
        
        switch ($this->platform){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                
                if(isset($data['contract_code'])) $map['symbol']=$data['contract_code'];
                
                //判断是期货还是现货
                switch ($this->checkType($map['symbol'])){
                    case 'future':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='buy';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;
                        
                        //市价单与限价单的参数映射
                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='buy';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;
                        
                        //市价单与限价单的参数映射
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
                        
                        //市价单与限价单的参数映射
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

                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['price']=$data['_price'];
                    $map['ordType']=$data['ordType'] ?? 'Limit';
                }else{
                    $map['ordType']=$data['ordType'] ?? 'Market';
                }
                
                //支持原生参数
                $data['side']=$map['side'];
                break;
            }
            case 'okex':{
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];
                $map['order_type']=$data['order_type'] ?? 0;
                
                switch ($this->checkType($map['instrument_id'])){
                    case 'spot':{
                        $data['side']=$map['side']='buy';
                        $map['margin_trading']=1;
                        
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
                            $map['match_price']=0;
                            $map['price']=$data['_price'];
                            $map['size']=$data['_number'] ?? 0;
                        }else{
                            $map['match_price']=1;
                            $map['size']=$data['_number'] ?? 0;
                        }
                        
                        $map['leverage']=$data['leverage'] ?? 10;
                        break;
                    }
                }
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                $map['side']='BUY';
                
                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);
            
                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['price']=$data['_price'] ?? ($data['price'] ?? '');
                    $map['type']='LIMIT';
                }else{
                    $map['type']='MARKET';
                }
                
                switch ($this->checkType()){
                    case 'future':{
                        break;
                    }
                    case 'spot':{
                        $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                        break;
                    }
                }
                
                //支持原生参数
                $data['side']=$map['side'];
                
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
        
        switch ($this->platform){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                
                if(isset($data['contract_code'])) $map['symbol']=$data['contract_code'];
                
                //判断是期货还是现货
                switch ($this->checkType($map['symbol'])){
                    case 'future':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='sell';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;
                        
                        //市价单与限价单的参数映射
                        if(isset($data['_price'])){
                            $map['price']=$data['_price'];
                            $map['order_price_type']='limit';
                        }else {
                            $map['order_price_type']='optimal_20';
                        }
                        break;
                    }
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        unset($map['symbol']);
                        $map['volume']=$data['_number'] ?? $data['volume'];
                        $data['direction']=$map['direction']='sell';
                        $map['offset']=$data['offset'] ?? ($data['_entry'] ? 'open' : 'close');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['lever_rate']=$data['lever_rate'] ?? 20;
                        
                        //市价单与限价单的参数映射
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
                        
                        //市价单与限价单的参数映射
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
                
                //支持原生参数
                $data['side']=$map['side'];
                
                break;
            }
            case 'okex':{
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];
                $map['order_type']=$data['order_type'] ?? 0;
                
                switch ($this->checkType($map['instrument_id'])){
                    case 'spot':{
                        $data['side']=$map['side']='sell';
                        $map['margin_trading']=1;
                        
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
                            $map['match_price']=0;
                            $map['price']=$data['_price'];
                            $map['size']=$data['_number'] ?? 0;
                        }else{
                            $map['match_price']=1;
                            $map['size']=$data['_number'] ?? 0;
                        }
                        
                        //判断是否是交割合约
                        if(!stripos($map['instrument_id'],'SWAP')){
                            $map['leverage']=$data['leverage'] ?? 10;
                        }
                        break;
                    }
                }
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                $map['side']='SELL';
                
                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);
                
                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['price']=$data['_price'] ?? ($data['price'] ?? '');
                    $map['type']='LIMIT';
                }else{
                    $map['type']='MARKET';
                }
                
                switch ($this->checkType()){
                    case 'future':{
                        break;
                    }
                    case 'spot':{
                        $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                        break;
                    }
                }
                
                //支持原生参数
                $data['side']=$map['side'];
                
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
        switch ($this->platform){
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
                    case 'swap':{
                        $map['contract_code']=$data['contract_code'] ?? $map['symbol'];
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        
                        if(empty($map['order_id'])) unset($map['order_id']);
                        if(empty($map['client_order_id'])) unset($map['client_order_id']);
                        unset($map['symbol']);
                        break;
                    }
                    case 'spot':{
                        $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');
                        
                        $map['client-order-id']=$data['_client_id'] ?? ($data['client-order-id'] ?? '');
                        if(empty($map['client-order-id'])) unset($map['client-order-id']);
                        
                        break;
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
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
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
        
        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;
        
        return $map;
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
        
        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;
        
        return $map;
    }
    
    /**
     *
     * */
    function show(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                switch ($this->checkType($data['_symbol'] ?? ($data['symbol'] ?? ($data['contract_code'] ?? '')))){
                    case 'future':{
                        $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                        $map['client_order_id']=$data['_client_id'] ?? ($data['client_order_id'] ?? '');
                        $map['symbol']=$data['_symbol'] ?? ($data['symbol'] ?? '');
                        break;
                    }
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
                $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
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
        
        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;
        
        return $map;
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
        
        //检测是否原生参数
        if($this->checkOriginalParam($data)) return $data;
        
        return $map;
    }
}


