<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/binance-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['binance']['key'];
$secret=$keysecret['binance']['secret'];

$exchanges=new Exchanges('binance',$key,$secret);

$action=intval($_GET['action'] ?? 0);

switch ($action){
    //******************************现货
    //***********现货市价交易
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
        ]);
        break;
    }
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
        ]);
        break;
    }
    //***********现货限价交易
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
            '_price'=>'2000',
        ]);
        break;
    }
    case 151:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
            '_price'=>'9000',
        ]);
        break;
    }
    
    //******************************现货一个订单完整流程
    case 300:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
            '_price'=>'2000',
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>$result['orderId'],
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>$result['orderId'],
        ]);
        
        break;
    }
    
    //******************************期货一个订单完整流程
    case 350:{
        
        
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
print_r($result);