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

//Support for more request Settings
$exchanges->setOptions([
    //Set the request timeout to 60 seconds by default
    'timeout'=>10,
    
    //If you are developing locally and need an agent, you can set this
    'proxy'=>true,
    //More flexible Settings
    /* 'proxy'=>[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     ], */
    //Close the certificate
    'verify'=>false,
]);

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********system error
    //exception testing
    case 1:{
        $exchanges=new Exchanges('okex','exception','exception','exception');
        $result=$exchanges->trader()->buy([
            '_symbol'=>'exception testing',
            '_price'=>'10',
        ]);
        
        /*
        Array
        (
            [_error] => Array
                (
                    [code] => 30006
                    [message] => Invalid OK-ACCESS-KEY
                    [_method] => POST
                    [_url] => https://www.okex.com/api/spot/v3/orders
                    [_httpcode] => 401
                )
        )
         */
        break;
    }
    
    case 2:{
        //If you are developing locally and need an agent, you can set this
        $exchanges->setOptions([
            'proxy'=>true,
        ]);
        
        //More flexible Settings
        $exchanges->setOptions([
            'proxy'=>[
                'http'  => 'http://127.0.0.1:12333',
                'https' => 'http://127.0.0.1:12333',
                'no'    =>  ['.cn']
            ],
        ]);
        
        //Get the information of holding positions of a contract.
        $result=$exchanges->account()->get([
            '_symbol'=>'BTC-USD-190927',
        ]);
        print_r($result);
        
        //This endpoint supports getting the balance, amount available/on hold of a token in spot account.
        $result=$exchanges->account()->get([
            '_symbol'=>'BTC',
        ]);
        break;
    }
    
    //******************************Spot
    //***********Spot Market
    case 100:{
        $result=$exchanges->trader()->buy([
            //'_symbol'=>'DASH-USDT',
            '_symbol'=>'ADA-USDT',
            '_price'=>'0.5',
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
            '_symbol'=>'DASH-USDT',
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
            '_number'=>'1',
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
    
    case 180:{
        //This endpoint supports getting the balance, amount available/on hold of a token in spot account.
        $result=$exchanges->account()->get([
            '_symbol'=>'BTC',
        ]);
        break;
    }
    
    case 181:{
        //Place an Order
        $result=$exchanges->getPlatform('spot')->order()->post([
            'instrument_id'=>'btc-usdt',
            'side'=>'buy',
            'price'=>'100',
            'size'=>'0.001',
            
            //'type'=>'market',
            //'notional'=>'100'
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
        //It's the same as that  => case 211
        //It's the opposite of that  => case 201
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        
        /* $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]); */
        break;
    }
    case 201:{
        //It's the opposite of that  => case 200
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>false,//open short
            //'_client_id'=>'custom ID',
        ]);
        
        /* $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>false,//open short
            //'_client_id'=>'custom ID',
        ]); */
        break;
    }
    
    
    
    
    
    case 203:{
        //It's the opposite of that  => case 204
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 204:{
        //It's the opposite of that  => case 203
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>false,//close long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 211:{
        //It's the same as that  => case 200
        //The original parameters
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'BTC-USD-190927',
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
    
    case 221:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'instrument_id'=>'BTC-USD-190927',
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
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_entry'=>false,//close short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    //***********Future Limit
    case 250:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_price'=>'2000',
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 251:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_price'=>'99999',
            '_entry'=>false,//open short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 261:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'BTC-USD-190927',
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
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_price'=>'1000',
            '_entry'=>true,//close long
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 253:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_price'=>'1000',
            '_entry'=>false,//close short
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 301:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTC-USD-190927',
            '_order_id'=>'2671566274710528',
        ]);
        break;
    }
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'instrument_id'=>'BTC-USD-190927',
            'order_id'=>'2671566274710528',
        ]);
        break;
    }
    
    case 303:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'instrument_id'=>'BTC-USD-190927',
            'order_id'=>'2671566274710528',
        ]);
        break;
    }
    
    case 304:{
        //Get the information of holding positions of a contract.
        $result=$exchanges->account()->get([
            '_symbol'=>'BTC-USD-190927',
        ]);
        break;
    }
    
    case 305:{
        //Place an Order
        $result=$exchanges->getPlatform('future')->order()->post([
            'instrument_id'=>'btc-usd-190927',
            'type'=>'1',
            'price'=>'100',
            'size'=>'1',
        ]);
        break;
    }
    
    case 306:{
        $result=$exchanges->getPlatform('future')->position()->get();
        break;
    }
    
    
    
    //***Complete future flow
    case 450:{
        $_client_id=md5(rand(1,999999999));//custom ID
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-190927',
            '_number'=>'1',
            '_price'=>'2000',
            '_entry'=>true,//open long
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USD-190927',
            '_client_id'=>$_client_id,
        ]);
        
        break;
    }
    
    //******************************Swap
    //***********Swap Market
    //Complete swap flow
    case 500:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-SWAP',
            '_number'=>'1',
            '_price'=>'5000',
            '_entry'=>true,//open long
            //'_client_id'=>'custom ID',
        ]);
        print_r($result); 
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTC-USD-SWAP',
            '_order_id'=>$result['_order_id'],
        ]);
        break;
    }
    
    case 501:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTC-USD-SWAP',
            '_order_id'=>'270553126514663424'
        ]);
        break;
    }
    
    case 502:{
        //Get the information of holding positions of a contract.
        $result=$exchanges->account()->get([
            '_symbol'=>'BTC-USD-SWAP',
        ]);
        break;
    }
    
    case 503:{
        $result=$exchanges->trader()->buy([
            'instrument_id'=>'BTC-USD-SWAP',
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
    
    case 504:{
        //It's the opposite of that  => case 505
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-SWAP',
            '_number'=>'1',
            '_entry'=>true,//open long
        ]);
        break;
    }
    
    case 505:{
        //It's the opposite of that  => case 504
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-SWAP',
            '_number'=>'1',
            '_entry'=>false,//open long
        ]);
        break;
    }
    
    case 506:{
        //It's the opposite of that  => case 507
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-SWAP',
            '_number'=>'1',
            '_entry'=>true,//open long
        ]);
        break;
    }
    
    case 507:{
        //It's the opposite of that  => case 506
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-SWAP',
            '_number'=>'1',
            '_entry'=>false,//open long
        ]);
        break;
    }
    
    //***********Swap Limit
    case 510:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTC-USD-SWAP',
            '_price'=>'10000',
            '_number'=>'1',
            '_entry'=>true,//open long
        ]);
        break;
    }
    
    case 511:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTC-USD-SWAP',
            '_price'=>'15000',
            '_number'=>'1',
            '_entry'=>true,//open long
        ]);
        break;
    }
    
    case 600:{
        //Place an Order
        $result=$exchanges->getPlatform('swap')->order()->post([
            'instrument_id'=>'BTC-USD-SWAP',
            'type'=>'1',
            'price'=>'5000',
            'size'=>'1',
        ]);
        break;
    }
    
    
    case 1001:{
        //Public API
        $exchanges=new Exchanges('okex');
        $exchanges->setOptions([
            'timeout'=>10,
            'proxy'=>true,
            'verify'=>false,
        ]);
        $result=$exchanges->getPlatform('spot')->instrument()->getBook([
            'instrument_id'=>'BTC-USDT',
            'size'=>20
        ]);
        break;
    }
    
    case 1002:{
        $result=$exchanges->getPlatform('future')->fill()->get([
            'instrument_id'=>"BTC-USD-190920",
            "limit"=>15
        ]);
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}

print_r($result);
