<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/huobi-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['huobi']['key'];
$secret=$keysecret['huobi']['secret'];
$host=$keysecret['huobi']['host'];
$account_id=$keysecret['huobi']['account_id'];

$exchanges=new Exchanges('huobi',$key,$secret,$account_id,$host);

$action=intval($_GET['action'] ?? 0);//http 模式
if(empty($action)) $action=intval($argv[1]);//cli 模式

switch ($action){
    //******************************现货
    //***********现货市价交易
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_price'=>'5',
        ]);
        break;
    }
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
        ]);
        break;
    }
    //***********现货限价交易
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        break;
    }
    case 151:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'99999',
        ]);
        break;
    }
    //******************************期货
    //***********期货市价交易
    //等待补充
    
    //******************************现货一个订单完整流程
    case 300:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->show([
            '_order_id'=>$result['data'],
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->cancel([
            '_order_id'=>$result['data']['id'],
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