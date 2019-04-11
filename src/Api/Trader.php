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
     * _entryclosed   true:开仓   false:平仓。只有_future有值才有效
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
        $data=$this->map->trader()->sell($data);
        
        $this->platform->trader()->sell($data);
    }
    
    /**
     *
     * */
    function buy(array $data){
        
    }
    
    /**
     *
     * */
    function cancel(array $data){
        
    }
    
    /**
     *
     * */
    function update(array $data){
        
    }
    
    /**
     *
     * */
    function show(array $data){
        
    }
    
    /**
     *
     * */
    function showAll(array $data){
        
    }
}