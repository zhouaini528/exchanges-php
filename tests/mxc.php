<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitfinex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['mxc']['key'];
$secret=$keysecret['mxc']['secret'];

$exchanges=new Exchanges('mxc',$key,$secret);

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
    case 1:{
        try {
            $result=$exchanges->getPlatform()->account()->getInfo();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    case 2:{
        try {
            $result=$exchanges->getPlatform()->common()->getPing();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('spot')->common()->getRateLimit();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        
        try {
            $result=$exchanges->getPlatform('spot')->common()->getTimestamp();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    case 3:{
        try {
            $result=$exchanges->getPlatform()->market()->getDeals([
                'symbol'=>'btc_usdt',
                'limit'=>2,
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getDepth([
                'depth'=>2,
                'symbol'=>'btc_usdt'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getTicker([
                'symbol'=>'btc_usdt',
                'limit'=>2
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getKline([
                'symbol'=>'btc_usdt',
                'interval'=>'1h',
                //'limit'=>10
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getSymbols();
            print_r($result['data'][0]);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    case 4:{
        //Place an Order
        try {
            $result=$exchanges->getPlatform()->order()->postPlace([
                'symbol'=>'EOS_USDT',
                'price'=>'6',
                'quantity'=>1,
                'trade_type'=>'ASK',//BID=buy，ASK=sell
                'order_type'=>'LIMIT_ORDER',//LIMIT_ORDER，POST_ONLY，IMMEDIATE_OR_CANCEL
                //'client_order_id'=>''
                
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        //Get order details by order ID.
        try {
            $result=$exchanges->getPlatform()->order()->getQuery([
                'symbol'=>'EOS_USDT',
                'order_ids'=>$result['data'],
                //'client_order_ids'=>'',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        //Cancelling an unfilled order.
        try {
            $result=$exchanges->getPlatform()->order()->deleteCancel([
                'symbol'=>'EOS_USDT',
                'order_ids'=>$result['data'][0]['id'],
                //'client_order_ids'=>'',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    default:{
        echo 'nothing';
        //exit;
    }
}