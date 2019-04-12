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

$action=intval($_GET['action']);

switch ($action){
    //******************************现货
    //***********现货市价交易
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_price'=>'5',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    //***********现货限价交易
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            '_price'=>'2000',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 151:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            '_price'=>'99999',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    //******************************期货
    //***********期货市价交易
    case 200:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>true,//开多
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 201:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>false,//开空
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 202:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>true,//平多
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 203:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>false,//平空
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    //***********期货限价交易
    case 250:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'2000',
            '_future'=>true,
            '_entry'=>true,//开多
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 251:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'99999',
            '_future'=>true,
            '_entry'=>false,//开空
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 252:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'1000',
            '_future'=>true,
            '_entry'=>true,//平多
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 253:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'1000',
            '_future'=>true,
            '_entry'=>false,//平空
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    default:{
        echo 'nothing';
        exit;
    }
}

print_r($result);