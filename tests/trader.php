<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitmex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

$key='';
$secret='';
$host='https://testnet.bitmex.com';

$exchanges=new Exchanges('okex',$key,$secret,$extra='',$host='');

//******************************现货
//***********市价交易
//buy时  _symbol  _number 必填参数
/*$exchanges->trader()->buy([
    '_symbol'=>'币种',
    '_price'=>'购买价格',
    
    //'_client_id'=>'自定义ID',
]);

//sell 时  _symbol  _number 必填参数
$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    
    //'_client_id'=>'自定义ID',
]);

//***********限价交易 
$exchanges->trader()->buy([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    '_price'=>'购买价格',
    
    //'_client_id'=>'自定义ID',
]);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    '_price'=>'购买价格',
    
    //'_client_id'=>'自定义ID',
]);
*/
//******************************期货
//***********市价交易
$exchanges->trader()->buy([
    '_symbol'=>'币种',
    '_price'=>'购买价格',
    '_future'=>true,
    '_entry'=>true,//对bitmex 默认
    
    //'_client_id'=>'自定义ID',
]);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    '_future'=>true,
    '_entry'=>false,//对bitmex 默认
    
    //'_client_id'=>'自定义ID',
]);

//***********限价交易 
$exchanges->trader()->buy([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    '_price'=>'购买价格',
    '_future'=>true,
    '_entry'=>true,//对bitmex 默认
    
    //'_client_id'=>'自定义ID',
]);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    '_price'=>'购买价格',
    '_future'=>true,
    '_entry'=>false,//对bitmex 默认
    
    //'_client_id'=>'自定义ID',
]);



