<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Interfaces\TraderInterface;

class Trader extends Base implements TraderInterface
{
    /**
     * 卖
     * 统一必填参数
     * _symbol   币种
     * _number  购买数量
     * *****************以上参数必填写
     * _price      当前价格    填写参数为：限价交易    不填写为：市价交易
     * _client_id  自定义ID
     * 
     * _future    是否现货与期货，false：现货   true：期货   默认：false
     * _entry   true:开仓   false:平仓。只有_future有值才有效
     * *****************以上参数非必填写
     * @return [
     * _status=>-2,-1,0,1,2   '完成交易'=>1,'挂单中'=>0, '部分完成'=>2,'撤单'=>-1,'系统错误'=>-2,
     * _order_id 订单ID
     * _client_id 自定义ID
     * _filled_qty  => 返回已经成功  成交的仓位
     * _price_avg =>  当前交易平均价格
     * ]  
     * */
    function sell(array $data){
        try {
            $map=$this->map->request_trader()->sell($data);
            $result=$this->platform->trader()->sell($map);
            return $this->map->response_trader()->sell($result);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    
    /**
     *
     * */
    function buy(array $data){
        try {
            $map=$this->map->request_trader()->buy($data);
            $result=$this->platform->trader()->buy($map);
            return $this->map->response_trader()->buy($result);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    
    /**
      * 删除订单 即撤单
     * 请求参数
     * '_order_id'   与  _client_id 必须有一个存在
        '_symbol'=>'',
     * *****************以上参数必填写   
     * _order_id  第三方平台ID
     * _client_id  自定义ID
     * _future  是否现货与期货，false：现货   true：期货   默认：false
     * *****************以上参数非必填写    
     * 
     * @return [_status=>-1,0,1]   '挂单中'=>0,  '撤单成功'=>1   失败=>-1,
     * */
    function cancel(array $data){
        try {
            $map=$this->map->request_trader()->cancel($data);
            $result=$this->platform->trader()->cancel($map);
            return $this->map->response_trader()->cancel($result);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    
    /**
     *
     * */
    function update(array $data){
        
    }
    
    /**
     * 查询订单
     * 请求参数
     * '_order_id'   与  _client_id 必须有一个存在
        '_symbol'=>'',
     * *****************以上参数必填写   
     * _order_id  第三方平台ID
     * _client_id  自定义ID
     * _future  是否现货与期货，false：现货   true：期货   默认：false
     * *****************以上参数非必填写   
     * @return [
     * _status=>-2,-1,0,1,2   '完成交易'=>1,'挂单中'=>0, '部分完成'=>2,'撤单'=>-1,'系统错误'=>-2,
     * _filled_qty  => 返回已经成功  成交的仓位
     * _price_avg =>  当前交易平均价格
     * _order_id 订单ID
     * _client_id  自定义ID
     * ] 
     * */
    function show(array $data){
        try {
            $map=$this->map->request_trader()->show($data);
            $result=$this->platform->trader()->show($map);
            return $this->map->response_trader()->show($result);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    
    /**
     *
     * */
    function showAll(array $data){
        
    }
}