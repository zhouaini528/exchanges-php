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
    function sell(array $data){
        switch ($this->platform){
            case 'huobi':{
                
                break;
            }
            case 'bitmex':{
                
                break;
            }
            case 'okex':{
                $map=[];
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];
                $map['order_type']=$data['order_type'] ?? 0;
                
                if(isset($data['_future']) && $data['_future']){
                    /*
                    参数名	参数类型	是否必须	描述
                    client_oid	String	否	由您设置的订单ID来识别您的订单 ,类型为字母（大小写）+数字或者纯字母（大小写）， 1-32位字符
                    instrument_id	String	是	合约ID，如BTC-USD-180213
                    type	String	是	1:开多2:开空3:平多4:平空
                    order_type	string	否	参数填数字，0：普通委托（order type不填或填0都是普通委托） 1：只做Maker（Post only） 2：全部成交或立即取消（FOK） 3：立即成交并取消剩余（IOC）
                    size	Number	是	买入或卖出合约的数量（以张计数）
                    match_price	String	否	是否以对手价下单(0:不是 1:是)，默认为0，当取值为1时。price字段无效
                    price	是	每张合约的价格
                    
                    交割合约有该参数
                    leverage	Number	是	要设定的杠杆倍数，10或20
                    */
                    $map['type']=$data['type'] ?? ($data['_entry']?3:4);
                    
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['match_price']=0;
                        $map['price']=$data['_price'];
                        $map['size']=$data['_number'];
                    }else{
                        $map['match_price']=1;
                        $map['size']=$data['_number'];
                    }
                    
                    //判断是否是交割合约
                    if(!stripos($map['instrument_id'],'SWAP')){
                        $map['leverage']=$data['leverage'] ?? 10;
                    }
                }else{
                    /*
                     * 参数名	类型	是否必填	描述
                     client_oid	string	否	由您设置的订单ID来识别您的订单,类型为字母（大小写）+数字或者纯字母（大小写） ，1-32位字符
                     type	string	否	limit，market(默认是limit)
                     side	string	是	buy or sell
                     instrument_id	string	是	币对名称
                     order_type	string	否	参数填数字，0：普通委托（order type不填或填0都是普通委托） 1：只做Maker（Post only） 2：全部成交或立即取消（FOK） 3：立即成交并取消剩余（IOC）
                     margin_trading	byte	否	下单类型(当前为币币交易，请求值为1)
                     限价单特殊参数
                     参数名	类型	是否必填	描述
                     price	string	是	价格
                     size	string	是	买入或卖出的数量
                     市价单特殊参数
                     参数名	类型	是否必填	描述
                     size	string	是	卖出数量，市价卖出时必填size
                     notional	string	是	买入金额，市价买入是必填notional
                     * */
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
                }
                
                return $map;
            }
            case 'binance':{
                
                break;
            }
        }
        
        return $data;
    }
    
    /**
     *
     * */
    function buy(array $data){
        switch ($this->platform){
            case 'huobi':{
                
                break;
            }
            case 'bitmex':{
                
                break;
            }
            case 'okex':{
                $map=[];
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['instrument_id']=$data['_symbol'] ?? $data['instrument_id'];
                $map['order_type']=$data['order_type'] ?? 0;
                
                //判断是期货还是现货
                if(isset($data['_future']) && $data['_future']){
                    $map['type']=$data['type'] ?? ($data['_entry']?1:2);
                    
                    //市价单与限价单的参数映射
                    if(isset($data['_number']) && isset($data['_price'])){
                        $map['match_price']=0;
                        $map['price']=$data['_price'];
                        $map['size']=$data['_number'];
                    }else{
                        $map['match_price']=1;
                        $map['size']=$data['_number'];
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
                }
                
                return $map;
            }
            case 'binance':{
                
                break;
            }
        }
        
        return $data;
    }
    
    /**
     *
     * */
    function cancel(array $data){
        switch ($this->platform){
            case 'huobi':{
                
                break;
            }
            case 'bitmex':{
                
                break;
            }
            case 'okex':{
                $map=[];
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
                
                return $map;
            }
            case 'binance':{
                
                break;
            }
        }
        
        return $data;
    }
    
    /**
     *
     * */
    function update(array $data){
        return $data;
    }
    
    /**
     *
     * */
    function show(array $data){
        switch ($this->platform){
            case 'huobi':{
                
                break;
            }
            case 'bitmex':{
                
                break;
            }
            case 'okex':{
                $map=[];
                $map['order_id']=$data['_order_id'] ?? ($data['order_id'] ?? '');
                $map['client_oid']=$data['_client_id'] ?? ($data['client_oid'] ?? '');
                $map['order_id']=empty($map['order_id']) ? $map['client_oid'] : $map['order_id'];
                $map['instrument_id']=$data['_symbol'] ?? ($data['instrument_id'] ?? '');
                
                return $map;
            }
            case 'binance':{
                
                break;
            }
        }
        
        return $data;
    }
    
    /**
     *
     * */
    function showAll(array $data){
        return $data;
    }
}


