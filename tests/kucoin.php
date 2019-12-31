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

//kucoin
$key=$keysecret['ku']['kucoin']['key'];
$secret=$keysecret['ku']['kucoin']['secret'];
$passphrase=$keysecret['ku']['kucoin']['passphrase'];
$host=$keysecret['ku']['kucoin']['host'];
$kucoin=new Exchanges('kucoin',$key,$secret,$passphrase,$host);

//kumex
$key=$keysecret['ku']['kumex']['key'];
$secret=$keysecret['ku']['kumex']['secret'];
$passphrase=$keysecret['ku']['kumex']['passphrase'];
$host=$keysecret['ku']['kumex']['host'];
$kumex=new Exchanges('kucoin',$key,$secret,$passphrase,$host);


//Support for more request Settings
$kucoin->setOptions([
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

$kumex->setOptions([
    'proxy'=>true,
    'verify'=>false,
]);


$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********system error
    //exception testing
    case 1:{
        $result=$kucoin->trader()->buy([
            '_client_id'=>'exception testing',
            '_symbol'=>'exception testing',
            '_number'=>'exception testing',
        ]);
        
        /*
        Array
        (
            [_error] => Array
                (
                    [code] => 400000
                    [msg] => Bad Request
                    [_method] => POST
                    [_url] => https://openapi-sandbox.kucoin.com/api/v1/orders
                    [_httpcode] => 400
                )
        
        )
         */
        break;
    }
    
    case 2:{
        $result=$kumex->trader()->buy([
            '_client_id'=>'exception testing',
            '_symbol'=>'exception testing',
            '_number'=>'exception testing',
        ]);
        break;
    }
    
    //***********Trader Market
    case 100:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kucoin->trader()->buy([
            '_client_id'=>$client_id,
            '_symbol'=>'ETH-BTC',
            '_number'=>'0.001',
        ]);
        print_r($result);
        die;
        //The original parameters
        $result=$kucoin->trader()->buy([
            'clientOid'=>$client_id,
            'symbol'=>'ETH-BTC',
            'size'=>'0.001',
            'type'=>'market'
        ]);
        
        break;
    }
    case 101:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kucoin->trader()->sell([
            '_client_id'=>$client_id,
            '_symbol'=>'ETH-BTC',
            '_number'=>'0.001',
        ]);
        print_r($result);
        
        //The original parameters
        $result=$kucoin->trader()->sell([
            'clientOid'=>$client_id,
            'symbol'=>'ETH-BTC',
            'size'=>'0.001',
            'type'=>'market'
        ]);
        break;
    }
    
    //Get Account
    case 102:{
        $result=$kucoin->account()->get();
        print_r($result);
        
        $result=$kucoin->account()->get([
            '_symbol'=>'5d5cbaa1ef83c753ca6ddd7f',
        ]);
        break;
    }
    
    case 103:{
        $result=$kucoin->trader()->show([
            //'_order_id'=>'5d6e0d26ef83c7622be82eb0',
        ]);
        break;
    }
    
    //***********Trader Limit
    case 200:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kucoin->trader()->buy([
            '_client_id'=>$client_id,
            '_symbol'=>'ETH-BTC',
            '_number'=>'0.001',
            '_price'=>'0.00001',
        ]);
        print_r($result);
        
        //The original parameters
        $result=$kucoin->trader()->buy([
            'clientOid'=>$client_id,
            'symbol'=>'ETH-BTC',
            'size'=>'0.001',
            'type'=>'limit',
            'price'=>'0.00001'
        ]);
        
        break;
    }
    case 201:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kucoin->trader()->sell([
            '_client_id'=>$client_id,
            '_symbol'=>'ETH-BTC',
            '_number'=>'0.001',
            '_price'=>'1',
        ]);
        print_r($result);
        
        //The original parameters
        $result=$kucoin->trader()->sell([
            'clientOid'=>$client_id,
            'symbol'=>'ETH-BTC',
            'size'=>'0.001',
            'type'=>'limit',
            'price'=>'1.1'
        ]);
        break;
    }
    
    case 202:{
        $result=$kucoin->trader()->cancel([
            '_order_id'=>'5d6e0d8fef83c7622be83320',
        ]);
        
        //The original parameters
        break;
    }
    
    
    //***********Complete flow
    case 400:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kucoin->trader()->sell([
            '_client_id'=>$client_id,
            '_symbol'=>'ETH-BTC',
            '_number'=>'0.001',
            '_price'=>'1',
        ]);
        print_r($result);
        
        $result=$kucoin->trader()->cancel([
            '_order_id'=>$result['_order_id'],
        ]);
        
        break;
    }
    
    //
    case 500:{
        $client_id=rand(10000,99999).rand(10000,99999);
        
        //The original object，
        $result=$kucoin->getPlatform()->order()->post([
            'clientOid'=>$client_id,
            'side'=>'buy',
            'symbol'=>'ETH-BTC',
            'price'=>'0.0001',
            'size'=>'10',
        ]);
        print_r($result);
        sleep(1);
        
        //Place an Order
        $result=$kucoin->getPlatform()->order()->get([
            'orderId'=>$result['data']['orderId']
        ]);
        print_r($result);
        sleep(1);
    
        $result=$kucoin->getPlatform()->order()->delete([
            'orderId'=>$result['data']['id']
        ]);
        
        break;
    }
    
    //***********Kumex
    case 600:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kumex->trader()->buy([
            '_client_id'=>$client_id,
            '_symbol'=>'XBTUSDM',
            '_number'=>'1',
        ]);
        print_r($result);
        
        
        //The original parameters
        $result=$kumex->trader()->buy([
            'clientOid'=>$client_id,
            'symbol'=>'XBTUSDM',
            'size'=>'1',
            'type'=>'market'
        ]);
        break;
    }
    
    case 601:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kumex->trader()->buy([
            '_client_id'=>$client_id,
            '_symbol'=>'XBTUSDM',
            '_number'=>'1',
            '_price'=>'7000'
        ]);
        print_r($result);
        
        //The original parameters
        $result=$kumex->trader()->buy([
            'clientOid'=>$client_id,
            'symbol'=>'XBTUSDM',
            'size'=>'1',
            'price'=>'7000',
            'type'=>'limit',
            'leverage'=>'20',
        ]);
        break;
    }
    
    case 602:{
        $result=$kumex->trader()->show([
            '_order_id'=>'5d6e187211527a5d67f5c12b',
        ]);
        print_r($result);
        
        $result=$kumex->trader()->show([
            'order-id'=>'5d6e187211527a5d67f5c12b',
        ]);
        break;
    }
    
    case 603:{
        $result=$kumex->trader()->cancel([
            '_order_id'=>'5d6e187211527a5d67f5c12b',
        ]);
        print_r($result);
        break;
    }
    
    //***********Complete flow
    case 700:{
        $client_id=rand(10000,99999).rand(10000,99999);
        $result=$kumex->trader()->buy([
            '_client_id'=>$client_id,
            '_symbol'=>'XBTUSDM',
            '_number'=>'1',
            '_price'=>'7000'
        ]);
        print_r($result);
        
        $result=$kumex->trader()->cancel([
            '_order_id'=>$result['_order_id'],
        ]);
        break;
    }
    
    //The original object，
    case 701:{
        $clientOid=rand(10000,99999).rand(10000,99999);
        
        $result=$kumex->getPlatform()->order()->post([
            'clientOid'=>$clientOid,
            'side'=>'buy',
            'symbol'=>'XBTUSDM',
            'leverage'=>10,
            
            'price'=>9000,
            'size'=>10,
        ]);
        print_r($result);
        sleep(1);
        
        $result=$kumex->getPlatform()->order()->get([
            'order-id'=>$result['data']['orderId'],
        ]);
        print_r($result);
        sleep(1);
        
        $result=$kumex->getPlatform()->order()->delete([
            'order-id'=>$result['data']['id'],
        ]);
        break;
    }
    
    //Get Position
    case 702:{
        $result=$kumex->account()->get();
        print_r($result);
        
        $result=$kumex->account()->get([
            '_symbol'=>'XBTUSDM',
        ]);
        
        break;
    }
    
    default:{
        echo 'nothing';
        //exit;
    }
}

print_r($result);