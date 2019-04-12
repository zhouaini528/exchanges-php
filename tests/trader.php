<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitmex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['ok']['key'];
$secret=$keysecret['ok']['secret'];
$extra=$keysecret['ok']['extra'];

$exchanges=new Exchanges('okex',$key,$secret,$extra);

//******************************现货
//***********市价交易
//buy时  _symbol  _number 必填参数
/* $result=$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_price'=>'5',
    
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

//sell 时  _symbol  _number 必填参数
/* $result=$exchanges->trader()->sell([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

//***********限价交易 
/* $result=$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    '_price'=>'2000',
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

/* $result=$exchanges->trader()->sell([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    '_price'=>'99999',
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

//******************************期货
//***********市价交易
/* $result=$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_future'=>true,
    '_entry'=>false,//对bitmex 默认
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

/* $result=$exchanges->trader()->sell([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_future'=>true,
    '_entry'=>true,//对bitmex 默认
    
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

//***********限价交易 
/* $result=$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_price'=>'99999',
    '_future'=>true,
    '_entry'=>false,//对bitmex 默认
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die; */

$result=$exchanges->trader()->sell([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_price'=>'1000',
    '_future'=>true,
    '_entry'=>true,//对bitmex 默认
    //'_client_id'=>'自定义ID',
]);
print_r($result);
die;



