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

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********Trader Market
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 110:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'symbol'=>'XBTUSD',
            'orderQty'=>'1',
            'ordType'=>'Market',
        ]);
        break;
    }
    case 111:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'symbol'=>'XBTUSD',
            'orderQty'=>'1',
            'ordType'=>'Market',
        ]);
        break;
    }
    
    //***********Trader Limit
    case 200:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 201:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>999999
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 211:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'symbol'=>'XBTUSD',
            'price'=>'100',
            'orderQty'=>'1',
            'ordType'=>'Limit',
        ]);
        break;
    }
    
    case 212:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'symbol'=>'XBTUSD',
            'price'=>'9999',
            'orderQty'=>'1',
            'ordType'=>'Limit',
        ]);
        break;
    }
    
    case 300:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'XBTUSD',
            '_order_id'=>'7d03ac2a-b24d-f48c-95f4-2628e6411927',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 301:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'symbol'=>'XBTUSD',
            'orderID'=>'807772e6-fc86-ddcc-9237-a3d8b36e6bfe',
        ]);
        break;
    }
    
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'symbol'=>'XBTUSD',
            'orderID'=>'1bffaa7f-c945-3b78-e10a-37c87bff6152',
        ]);
        break;
    }
    
    case 303:{
        //bargaining transaction
        $result=$exchanges->account()->get([
            '_symbol'=>'XBTUSD'
        ]);
        break;
    }
    
    //***********Complete flow
    case 400:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'custom ID',
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'XBTUSD',
            '_order_id'=>$result['_order_id'],
            //'_client_id'=>'custom ID',
        ]);
        
        break;
    }
    
    //
    case 500:{
        //The original object，
        $result=$exchanges->getPlatform()->order()->post([
            'symbol'=>'XBTUSD',
            'price'=>'100',
            'side'=>'Buy',
            'orderQty'=>'1',
            'ordType'=>'Limit',
        ]);
        break;
    }
    
    
    default:{
        echo 'nothing';
        //exit;
    }
}

print_r($result);