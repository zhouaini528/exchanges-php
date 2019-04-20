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
                
                //判断是期货还是现货
                if($this->checkFuture($map['symbol'])){
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        
                    }else {
                        
                    }
                }else{
                    $map['account-id']=$data['account-id'] ?? $this->extra;
                    
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['price']=$data['_price'];
                        $map['type']=$data['type'] ?? 'buy-limit';
                        $map['amount']=$data['_number'] ?? $data['amount'];
                    }else {
                        $map['type']=$data['type'] ?? 'buy-market';
                        $map['amount']=$data['_price'] ?? $data['amount'];//市价买单时表示买多少钱
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
                
                //判断是期货还是现货
                if($this->checkFuture($map['instrument_id'])){
                    $map['type']=$data['type'] ?? ($data['_entry']?1:2);
                    
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
                }else{
                    $map['side']='buy';
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
                    
                    //TODO 支持原生参数
                    $data['side']='buy';
                }
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                $map['side']='BUY';
                $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                
                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);
            
                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['price']=$data['_price'] ?? ($data['price'] ?? '');
                    $map['type']='LIMIT';
                }else{
                    $map['type']='MARKET';
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
                
                //判断是期货还是现货
                if($this->checkFuture($map['symbol'])){
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        
                    }else {
                        
                    }
                }else{
                    $map['account-id']=$data['account-id'] ?? $this->extra;
                    
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['price']=$data['_price'];
                        $map['type']=$data['type'] ?? 'sell-limit';
                        $map['amount']=$data['_number'] ?? $data['amount'];
                    }else {
                        $map['type']=$data['type'] ?? 'sell-market';
                        $map['amount']=$data['_number'] ?? $data['amount'];//市价卖单时表示卖多少币
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
                
                if($this->checkFuture($map['instrument_id'])){
                    $map['type']=$data['type'] ?? ($data['_entry']?3:4);
                    
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
                }else{
                    $map['side']='sell';
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
                    
                    //TODO 支持原生参数
                    $data['side']='sell';
                }
                
                break;
            }
            case 'binance':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                $map['newClientOrderId']=$data['_client_id'] ?? ($data['newClientOrderId'] ?? '');
                $map['quantity']=$data['_number'] ?? ($data['quantity'] ?? '');
                $map['side']='SELL';
                $map['newOrderRespType']=$data['newOrderRespType'] ?? 'ACK';
                
                if(empty($map['newClientOrderId'])) unset($map['newClientOrderId']);
                
                //市价单与限价单的参数映射
                if(isset($data['_number']) && isset($data['_price'])){
                    $map['timeInForce']=$data['timeInForce'] ?? 'GTC';
                    $map['price']=$data['_price'] ?? ($data['price'] ?? '');
                    $map['type']='LIMIT';
                }else{
                    $map['type']='MARKET';
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
                $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');
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
                $map['order-id']=$data['_order_id'] ?? ($data['order-id'] ?? '');
                break;
            }
            case 'bitmex':{
                $map['orderID']=$data['_order_id'] ?? ($data['orderID'] ?? '');
                $map['clOrdID']=$data['_client_id'] ?? ($data['clOrdID'] ?? '');
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
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


