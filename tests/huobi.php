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
        
        //bargaining transaction
        $result=$exchanges->account()->get([
            '_symbol'=>'btcusdt',
        ]);
        break;
    }
    
    //******************************Spot
    //***********Spot Market
    case 99:{
        //get $account_id,It's for buy and sell
        //recommended save database $account_id
        $exchanges=new Exchanges('huobi',$key,$secret);
        /* $exchanges->setOptions([
            'proxy'=>true,
            'verify'=>false
        ]); */
        $result=$exchanges->getPlatform('spot')->account()->get();
        break;
    }

    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_price'=>'20',
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
            '_order_id'=>'44997280257',
        ]);
        break;
    }
    
    case 301:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'order-id'=>'44997280257',
        ]);
        break;
    }
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'order-id'=>'44997280257',
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
    case 391:{
        echo $_client_id='abc'.rand(10000,99999).rand(10000,99999);
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
            '_client_id'=>$_client_id,
        ]);
        break;
    }
    
    case 392:{
        $result=$exchanges->trader()->show([
            '_client_id'=>'abc7083059759',
        ]);
        break;
    }
    
    case 393:{
        $result=$exchanges->trader()->cancel([
            '_client_id'=>'abc7083059759',
        ]);
        break;
    }
    
    
    case 397:{
        $_client_id=rand(10000,99999).rand(10000,99999);
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_client_id'=>$_client_id,
        ]);
        
        break;
    }
    case 398:{
        $_client_id=rand(10000,99999).rand(10000,99999);
        $result=$exchanges->trader()->sell([
            '_symbol'=>'eosusdt',
            '_number'=>'10',
            '_price'=>'3',
            '_client_id'=>$_client_id,
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_client_id'=>$_client_id,
        ]);
        
        break;
    }
    
    case 399:{
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
    
    case 400:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'eosusdt',
            '_number'=>'0.1',
            '_price'=>'20',
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_order_id'=>$result['data']['id'],
        ]);
        
        break;
    }
    
    
    
    //******************************Future
    //***********Future Market
    case 401:{
        //It's the same as that  => case 402
        //It's the opposite of that  => case 403
        $result=$exchanges->trader()->buy([
            '_symbol'=>'ETC191227',
            '_number'=>'1',
            '_entry'=>true,//true:open  false:close
        ]);
        
        break;
    }
    case 402:{
        //It's the same as that  => case 401
        $result=$exchanges->trader()->buy([
            'symbol'=>'XRP',//string	false	"BTC","ETH"...
            'contract_type'=>'quarter',//	string	false	Contract Type ("this_week": "next_week": "quarter":)
            'contract_code'=>'XRP190927',//	string	false	BTC180914
            //'price'=>'0.3',//	decimal	true	Price
            'volume'=>'1',//	long	true	Numbers of orders (amount)
            //'direction'=>'buy',//	string	true	Transaction direction
            'offset'=>'open',//	string	true	"open", "close"
            'order_price_type'=>'opponent',//"limit", "opponent"
            'lever_rate'=>20,//int	true	Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
        ]);
        
        break;
    }
    case 403:{
        //It's the opposite of that  => case 401
        $result=$exchanges->trader()->sell([
            '_symbol'=>'ETC191227',
            '_number'=>'1',
            '_entry'=>false,//true:open  false:close
        ]);
        
        break;
    }
    
    case 404:{
        //It's the opposite of that  => case 405
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XRP190927',
            '_number'=>'1',
            '_entry'=>true,//true:open  false:close
        ]);
        
        break;
    }
    
    case 405:{
        //It's the opposite of that  => case 404
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XRP190927',
            '_number'=>'1',
            '_entry'=>false,//true:open  false:close
        ]);
        
        break;
    }
    
    //***********Future Limit
    case 410:{
        //It's the same as that  => case 411
        $result=$exchanges->trader()->buy([
            '_symbol'=>'XRP190927',
            '_number'=>'1',
            '_price'=>'0.3',
            '_entry'=>true,//true:open  false:close
        ]);
        break;
    }
    
    case 411:{
        //It's the same as that  => case 410
        $result=$exchanges->trader()->buy([
            'symbol'=>'XRP',//string	false	"BTC","ETH"...
            'contract_type'=>'quarter',//	string	false	Contract Type ("this_week": "next_week": "quarter":)
            'contract_code'=>'XRP190927',//	string	false	BTC180914
            'price'=>'0.3',//	decimal	true	Price
            'volume'=>'1',//	long	true	Numbers of orders (amount)
            //'direction'=>'buy',//	string	true	Transaction direction
            'offset'=>'open',//	string	true	"open", "close"
            'order_price_type'=>'limit',//"limit", "opponent"
            'lever_rate'=>20,//int	true	Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
        ]);
        break;
    }
    
    case 412:{
        //It's the same as that  => case 413
        $result=$exchanges->trader()->sell([
            '_symbol'=>'XRP190927',
            '_number'=>'1',
            '_price'=>'0.4',
            '_entry'=>true,//true:open  false:close
        ]);
        break;
    }
    case 413:{
        //It's the same as that  => case 412
        $result=$exchanges->trader()->sell([
            'symbol'=>'XRP',//string	false	"BTC","ETH"...
            'contract_type'=>'quarter',//	string	false	Contract Type ("this_week": "next_week": "quarter":)
            'contract_code'=>'XRP190927',//	string	false	BTC180914
            'price'=>'0.4',//	decimal	true	Price
            'volume'=>'1',//	long	true	Numbers of orders (amount)
            //'direction'=>'buy',//	string	true	Transaction direction
            'offset'=>'open',//	string	true	"open", "close"
            'order_price_type'=>'limit',//"limit", "opponent"
            'lever_rate'=>20,//int	true	Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
        ]);
        break;
    }
    
    case 420:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'XRP190927',
            '_order_id'=>'2715696586',
        ]);
        break;
    }
    
    case 421:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'XRP190927',
            '_order_id'=>'2715696586',
        ]);
        break;
    }
    
    case 430:{
        $result=$exchanges->account()->get([
            '_symbol'=>'XRP190927',
        ]);
        break;
    }
    
    //***Complete future flow
    //TODO
    case 450:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'BCH200626',
            '_number'=>'1',
            '_price'=>'100',
            '_entry'=>true,//true:open  false:close
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BCH200626',
            '_order_id'=>$result['_order_id'],
        ]);
        
        break;
    }
    
    case 500:{
        $result=$exchanges->getPlatform('future')->contract()->postOrder([
            'symbol'=>'XRP',//string	false	"BTC","ETH"...
            'contract_type'=>'quarter',//	string	false	Contract Type ("this_week": "next_week": "quarter":)
            'contract_code'=>'XRP190927',//	string	false	BTC180914
            'price'=>'0.3',//	decimal	true	Price
            'volume'=>'1',//	long	true	Numbers of orders (amount)
            'direction'=>'buy',//	string	true	Transaction direction
            'offset'=>'open',//	string	true	"open", "close"
            'order_price_type'=>'limit',//"limit", "opponent"
            'lever_rate'=>20,//int	true	Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
        ]);
        break;
    }
    
    case 501:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'BCH200626',
            '_order_id'=>'700289577830793216',
        ]);
        break;
    }
    
    
    //******************************Swap
    case 610:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'ETH-USD',
            '_number'=>'1',
            '_price'=>'100',
            '_entry'=>true,//true:open  false:close
        ]);
        break;
    }
    case 611:{
        $result=$exchanges->trader()->show([
            '_symbol'=>'ETH-USD',
            '_order_id'=>'700034006594363392'
        ]);
        break;
    }
    
    case 612:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'ETH-USD',
            '_number'=>'1',
            '_price'=>'100',
            '_entry'=>true,//true:open  false:close
        ]);
        
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'ETH-USD',
            '_order_id'=>$result['_order_id'],
        ]);
        break;
    }
    
    case 613:{
        $result=$exchanges->trader()->cancel([
            '_symbol'=>'ETH-USD',
            '_order_id'=>'700280191934640128',
        ]);
        break;
    }
    
    case 1001:{
        //Public API
        $exchanges=new Exchanges('huobi');
        $exchanges->setOptions([
            'timeout'=>10,
            'proxy'=>true,
            'verify'=>false,
        ]);
        $result=$exchanges->getPlatform('spot')->market()->getDepth([
            'symbol'=>'btcusdt',
        ]);
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}

print_r($result);
