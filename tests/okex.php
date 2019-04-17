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

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //******************************Spot
    //***********Spot Market
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_price'=>'10',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 101:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'btc-usdt',
            'type'=>'market',
            'notional'=>'10'
        ]);
        break;
    }
    
    case 102:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 103:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'instrument_id'=>'btc-usdt',
            'size'=>'0.001',
            'type'=>'market',
        ]);
        break;
    }
    
    //***********Spot Limit
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            '_price'=>'2000',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 151:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            //'client_oid'=>'custom ID',
            'instrument_id'=>'btc-usdt',
            'price'=>'100',
            'size'=>'0.001',
        ]);
        break;
    }
    
    case 152:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USDT',
            '_number'=>'0.001',
            '_price'=>'99999',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 153:{
        //The original parameters
        $_client_id=md5(rand(1,999999999));//custom ID
        $result=$exchanges->trader()->sell([
            'instrument_id'=>'btc-usdt',
            'price'=>'9000',
            'size'=>'0.001',
            'client_oid'=>$_client_id,
        ]);
        break;
    }
    
    case 160:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTC-USDT',
            '_order_id'=>'2671215997495296',
        ]);
        
        break;
    }
    
    case 161:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'instrument_id'=>'BTC-USDT',
            'order_id'=>'2671215997495296',
        ]);
        
        break;
    }
    
    case 170:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USDT',
            '_order_id'=>'2671215997495296',
        ]);
        break;
    }
    
    case 171:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'instrument_id'=>'BTC-USDT',
            'order_id'=>'2671215997495296',
        ]);
        break;
    }
    
    
    //***Complete spot flow
    case 400:{
        $_client_id=md5(rand(1,999999999));//custom ID
        
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
    
    
    //******************************Future
    //***********Future Market
    case 200:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 211:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'BTC-USD-190628',
            'size'=>1,
            'type'=>1,//1:open long 2:open short 3:close long 4:close short
            //'price'=>2000,
            'leverage'=>10,//10x or 20x leverage
            'match_price' => 1,
            'order_type'=>0,
            //'client_oid'=>'custom ID',
        ]);
        break;
    }
    case 201:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>false,//open short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    
    case 202:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>true,//close long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 221:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'instrument_id'=>'BTC-USD-190628',
            'size'=>1,
            'type'=>3,//1:open long 2:open short 3:close long 4:close short
            //'price'=>2000,
            'leverage'=>10,//10x or 20x leverage
            'match_price' => 1,
            'order_type'=>0,
            //'client_oid'=>'custom ID',
        ]);
        break;
    }
    
    case 203:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_future'=>true,
            '_entry'=>false,//close short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    //***********Future Limit
    case 250:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'2000',
            '_future'=>true,
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 251:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'99999',
            '_future'=>true,
            '_entry'=>false,//open short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 261:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'BTC-USD-190628',
            'size'=>1,
            'type'=>1,//1:open long 2:open short 3:close long 4:close short
            'price'=>2000,
            'leverage'=>10,//10x or 20x leverage
            'match_price' => 0,
            'order_type'=>0,
            //'client_oid'=>'custom ID',
        ]);
        break;
    }
    
    
    case 252:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'1000',
            '_future'=>true,
            '_entry'=>true,//close long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 253:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'1000',
            '_future'=>true,
            '_entry'=>false,//close short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 301:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTC-USD-190628',
            '_order_id'=>'2671566274710528',
            '_future'=>true,
        ]);
        break;
    }
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'instrument_id'=>'BTC-USD-190628',
            'order_id'=>'2671566274710528',
        ]);
        break;
    }
    
    case 303:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'instrument_id'=>'BTC-USD-190628',
            'order_id'=>'2671566274710528',
        ]);
        break;
    }
    
    
    //***Complete future flow
    case 450:{
        $_client_id=md5(rand(1,999999999));//custom ID
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190628',
            '_number'=>'1',
            '_price'=>'2000',
            '_future'=>true,
            '_entry'=>true,//open long
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

print_r($result);