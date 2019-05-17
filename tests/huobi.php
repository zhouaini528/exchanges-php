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

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********system error
    //exception testing
    case 1:{
        $exchanges=new Exchanges('huobi','exception','exception');
        $result=$exchanges->trader()->buy([
            '_symbol'=>'exception testing',
            //'_price'=>'10',
        ]);
        
        /*
        Array
        (
            [_error] => Array
                (
                    [status] => error
                    [err-code] => api-signature-not-valid
                    [err-msg] => Signature not valid: Incorrect Access key [Access key错误]
                    [data] => 
                    [_method] => POST
                    [_url] => https://api.huobi.pro/v1/order/orders/place
                    [_httpcode] => 200
                )
        )
         */
        break;
    }
    
    //******************************Spot
    case 98:{
        //If you are developing locally and need an agent, you can set this
        $exchanges->setProxy('spot');
        
        //More flexible Settings
        $exchanges->setProxy('spot',[
            'http'  => 'http://127.0.0.1:12333',
            'https' => 'http://127.0.0.1:12333',
        ]);
        
        //bargaining transaction
        $result=$exchanges->account()->get([
            '_symbol'=>'btcusdt',
        ]);
        break;
    }
    //***********Spot Market
    case 99:{
        //get $account_id,It's for buy and sell
        //recommended save database $account_id
        $exchanges=new Exchanges('huobi',$key,$secret);
        $result=$exchanges->getPlatform('spot')->account()->get();
        break;
    }

    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_price'=>'10',
        ]);
        break;
    }
    case 110:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'buy-market',
            'amount'=>10
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
    case 111:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'sell-market',
            'amount'=>'0.001',
        ]);
        break;
    }
    //***********Spot Limit
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        break;
    }
    case 160:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'buy-limit',
            'amount'=>'0.001',
            'price'=>'2001',
        ]);
        break;
    }
    case 151:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'9999',
        ]);
        break;
    }
    case 161:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'sell-limit',
            'amount'=>'0.001',
            'price'=>'9998',
        ]);
        break;
    }
    
    case 300:{
        $result=$exchanges->trader()->show([
            '_order_id'=>'29897313869',
        ]);
        break;
    }
    
    case 301:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'order-id'=>'30002957180',
        ]);
        break;
    }
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'order-id'=>'30003632696',
        ]);
        break;
    }
    
    case 303:{
        $result=$exchanges->account()->get([
            '_symbol'=>'btcusdt',
        ]);
        break;
    }
    
    case 304:{
        $result=$exchanges->getPlatform('spot')->order()->postPlace([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'buy-limit',
            'amount'=>'0.001',
            'price'=>'100',
        ]);
        break;
    }
    
    //***Complete spot flow
    case 400:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_order_id'=>$result['data']['id'],
        ]);
        
        break;
    }
    
    
    
    //******************************Future
    //***********Future Market
    //TODO
    
    
    //***Complete future flow
    //TODO
    case 450:{
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}

print_r($result);