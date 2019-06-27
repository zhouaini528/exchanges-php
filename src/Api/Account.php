<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Interfaces\AccountInterface;

class Account extends Base implements AccountInterface
{
    /**
     * 现货返回查询余额，期货返回仓位数量
     * binance $exchanges->account()->get();返回所有货币对余额
     * bitmex $exchanges->account()->get();返回所有持仓
     * 
     * huobi spot $exchanges->account()->get(['_symbol'=>'btcusdt']);返回指定币对余额
     * 
     * okex spot $exchanges->account()->get(['_symbol'=>'BTC']);返回指定币对余额
     * okex future $exchanges->account()->get(['_symbol'=>'BTC-USD-190628',]);返回指定币对余额
     * 
     * @return array 返回原始数据
     * */
    function get(array $data=[]){
        try {
            $map=$this->map->request_account()->get($data);
            $result=$this->platform->account()->get($map);
            return $this->map->response_account()->get(['result'=>$result,'request'=>$data]);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}