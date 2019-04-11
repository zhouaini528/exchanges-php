<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitmex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';


$exchanges=new Exchanges('okex',[
    'key'=>$key,
    'secret'=>$secret,
]);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    
    //期货
    '_future'=>'是否现货与期货，0：现货   1：期货',
    '_entryclosed'=>'0:开仓   1:平仓',
    
    '_price'=>'当前价格    填写参数为：限价交易    不填写为：市价交易',
    '_client_id'=>'自定义ID',
    
    '原生接口参数'
]);