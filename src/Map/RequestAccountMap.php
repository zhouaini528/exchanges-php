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
    function get(array $data){
        $map=[];
        
        switch ($this->platform){
            case 'huobi':{
                $map['symbol']=$data['_symbol'] ?? $data['symbol'];
                
                //判断是期货还是现货
                switch ($this->checkType($map['symbol'])){
                    case 'spot':{
                        $map['account-id']=$data['account-id'] ?? $this->extra;
                        break;
                    }
                    case 'future':{
                        
                        break;
                    }
                }
                
                break;
            }
            case 'bitmex':{
                $symbol=$data['_symbol'] ?? ($data['symbol'] ?? '');
                
                if(!empty($symbol)) $map['filter']=json_encode(['symbol'=>$symbol]);
                
                break;
            }
            case 'okex':{
                $temp=$data['_symbol'] ?? ($data['instrument_id'] ?? $data['currency']);
                
                switch ($this->checkType($temp)){
                    case 'spot':{
                        $map['currency']=$temp;
                        break;
                    }
                    case 'future':{
                        $map['instrument_id']=$temp;
                        break;
                    }
                    case 'swap':{
                        $map['instrument_id']=$temp;
                        break;
                    }
                }
                
                break;
            }
            case 'binance':{
                break;
            }
            case 'kucoin':{
                $temp=$data['_symbol'] ?? ($data['accountId'] ?? ($data['symbol'] ?? ''));
                
                if(!empty($temp)){
                    switch ($this->checkType()){
                        case 'spot':{
                            $map['accountId']=$temp;
                            break;
                        }
                        case 'future':{
                            $map['symbol']=$temp;
                            break;
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
}


