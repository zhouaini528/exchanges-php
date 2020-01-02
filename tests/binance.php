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

$spot_host='https://api.binance.com';
$future_host='https://fapi.binance.com';

$exchanges=new Exchanges('binance',$key,$secret,$spot_host);

//Support for more request Settings
$exchanges->setOptions([
    //Set the request timeout to 60 seconds by default
    'timeout'=>10,
    
    //If you are developing locally and need an agent, you can set this
    //'proxy'=>true,
    //More flexible Settings
    /* 'proxy'=>[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     ], */
    //Close the certificate
    //'verify'=>false,
]);

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********system error
    //exception testing
    case 1:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'exception testing',
            '_number'=>'0.01',
        ]);
        
        /*
        return Array
        (
            [_error] => Array
                (
                    [code] => -1100
                    [msg] => Illegal characters found in parameter 'symbol'; legal range is '^[A-Z0-9_]{1,20}$'.
                    [_method] => POST
                    [_url] => https://api.binance.com/api/v3/order
                    [_httpcode] => 400
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
        
        //Get current account information.
        $result=$exchanges->account()->get();
        break;
    }
    
    //******************************Spot
    //***********Spot Market
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'10',
        ]);
        break;
    }
    case 101:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'symbol'=>'BTCUSDT',
            'type'=>'MARKET',
            'quantity'=>'0.01',
        ]);
        break;
    }
    
    case 102:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
        ]);
        break;
    }
    case 103:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'symbol'=>'BTCUSDT',
            'type'=>'MARKET',
            'quantity'=>'0.01',
        ]);
        break;
    }
    
    //***********Spot Limit
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
            '_price'=>'2000',
        ]); 
        break;
    }
    case 151:{
        //The original parameters
        $_client_id=md5(rand(1,999999999));//custom ID
        $result=$exchanges->trader()->buy([
            'newClientOrderId'=>$_client_id,
            'symbol'=>'BTCUSDT',
            'type'=>'LIMIT',
            'quantity'=>'0.01',
            'price'=>'2000',
            'timeInForce'=>'GTC',
        ]);
        break;
    }
    
    case 152:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.01',
            '_price'=>'9000',
        ]);
        break;
    }
    case 153:{
        //The original parameters
        $_client_id=md5(rand(1,999999999));//custom ID
        $result=$exchanges->trader()->sell([
            'newClientOrderId'=>$_client_id,
            'symbol'=>'BTCUSDT',
            'type'=>'LIMIT',
            'quantity'=>'0.01',
            'price'=>'9000',
            'timeInForce'=>'GTC',
        ]);
        break;
    }
    
    case 300:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>'324314658',
            //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
        ]);
        break;
    }
    case 301:{
        $result=$exchanges->trader()->show([
            'symbol'=>'BTCUSDT',
            'orderId'=>'324317395',
            //'origClientOrderId'=>'',
        ]);
        break;
    }
    
    case 302:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>'324314658',
        ]);
        break;
    }
    case 303:{
        $result=$exchanges->trader()->cancel([
            'symbol'=>'BTCUSDT',
            'orderId'=>'324317395',
            //'origClientOrderId'=>'',
        ]);
        break;
    }
    
    case 304:{
        //Get current account information.
        $result=$exchanges->account()->get();
        break;
    }
    
    
    //******************************Complete spot flow
    case 400:{
        $_client_id=md5(rand(1,999999999));//custom ID
        $result=$exchanges->trader()->sell([
            '_symbol'=>'ETHUSDT',
            '_number'=>'0.1',
            '_price'=>'500',
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
       $result=$exchanges->trader()->cancel([
            '_symbol'=>'ETHUSDT',
            //'_order_id'=>$result['orderId'],
            '_client_id'=>$_client_id,
        ]);
        
        break;
    }
    
    //******************************Complete future flow
    case 450:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.001',
            '_price'=>'6500',
        ]);
        
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>$result['orderId'],
            //'_client_id'=>$_client_id,
        ]);
        break;
    }
    
    
    case 461:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->account()->get();
        break;
    }
    
    case 462:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BTCUSDT',
            '_number'=>'0.001',
        ]);
        break;
    }
    
    case 463:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->trader()->buy([
            'symbol'=>'BTCUSDT',
            'quantity'=>'0.001',
            'type'=>'LIMIT',
            'price'=>'6500',
            'timeInForce'=>'GTC',
        ]);
        break;
    }
    
    case 463:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->trader()->buy([
            'symbol'=>'BTCUSDT',
            'quantity'=>'0.001',
            'type'=>'LIMIT',
            'price'=>'6500',
            'timeInForce'=>'GTC',
        ]);
        break;
    }
    
    case 464:{
        $exchanges=new Exchanges('binance',$key,$secret,$future_host);
        
        $result=$exchanges->trader()->show([
            '_symbol'=>'BTCUSDT',
            '_order_id'=>'487693783',
        ]);
        break;
    }
    
    case 1001:{
        //Public API
        $exchanges=new Exchanges('binance');
        $exchanges->setOptions([
            'timeout'=>10,
            'proxy'=>true,
            'verify'=>false,
        ]);
        $result=$exchanges->getPlatform()->system()->getDepth([
            'symbol'=>'BTCUSDT',
        ]);
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}
print_r($result);