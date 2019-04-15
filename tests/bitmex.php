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
$key=$keysecret['bitmex']['key'];
$secret=$keysecret['bitmex']['secret'];
$host=$keysecret['bitmex']['host'];
$extra='';

$exchanges=new Exchanges('bitmex',$key,$secret,$extra,$host);

$action=intval($_GET['action'] ?? 0);

switch ($action){
    //***********市价交易
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    
    //***********限价交易
    case 200:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    case 201:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>999999
            //'_client_id'=>'自定义ID',
        ]);
        break;
    }
    
    //***********完整流程
    case 300:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'自定义ID',
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->show([
            '_symbol'=>'XBTUSD',
            '_order_id'=>$result['orderID'],
            //'_client_id'=>'自定义ID',
        ]);
        print_r($result);
        sleep(1);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'XBTUSD',
            '_order_id'=>$result['orderID'],
            //'_client_id'=>'自定义ID',
        ]);
        
        break;
    }
    
    
    default:{
        echo 'nothing';
        //exit;
    }
}
print_r($result);