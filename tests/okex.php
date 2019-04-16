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
$passphrase=$keysecret['ok']['passphrase'];

$exchanges=new Exchanges('okex',$key,$secret,$passphrase);

$action=intval($_GET['action'] ?? 0);//http 模式
if(empty($action)) $action=intval($argv[1]);//cli 模式

switch ($action){
    //******************************现货
    //***********现货市价交易
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_price'=>'10',
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
    
    //******************************查询
    case 300:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTC-USDT',
            '_order_id'=>'2665536017542144',
        ]);
        
        break;
    }
    
    case 301:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USDT',
            '_order_id'=>'2665608002938880',
        ]);
        break;
    }
    
    //******************************现货一个订单完整流程
    case 400:{
        $_client_id=md5(rand(1,999999999));//自定义ID
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            '_price'=>'2000',
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USDT',
            '_client_id'=>$_client_id,
        ]);
        break;
    }
    
    //******************************期货一个订单完整流程
    case 450:{
        $_client_id=md5(rand(1,999999999));//自定义ID
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'2000',
            '_future'=>true,
            '_entry'=>true,//开多
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USD-190628',
            '_client_id'=>$_client_id,
            '_future'=>true,
        ]);
        
        break;
    }
    
    case 0:{
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}

/* $result=$exchanges->trader()->show([
    '_symbol'=>'BTC-USD-190628',
    '_order_id'=>'sdfsdfdsf',
    //'_client_id'=>'自定义ID',
]); */
print_r($result);