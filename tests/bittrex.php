<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bittrex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['bittrex']['key'];
$secret=$keysecret['bittrex']['secret'];

$exchanges=new Exchanges('bittrex',$key,$secret);

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
            $result=$exchanges->getPlatform()->market()->getSummaries();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getList();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    case 2:{
        try {
            $result=$exchanges->getPlatform()->account()->get();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->account()->getVolume();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    case 3:{
        //Place an Order
        try {
            $result=$exchanges->getPlatform()->order()->post([
                //'clientOrderId'=>'xxxxxxxx',
                'marketSymbol'=>'BTC-USD',
                'direction'=>'BUY',//BUY, SELL
                
                'type'=>'LIMIT',//LIMIT, MARKET, CEILING_LIMIT, CEILING_MARKET
                'quantity'=>'0.01',
                'limit'=>'3000',
                
                'timeInForce'=>'FILL_OR_KILL' //GOOD_TIL_CANCELLED, IMMEDIATE_OR_CANCEL, FILL_OR_KILL, POST_ONLY_GOOD_TIL_CANCELLED, BUY_NOW
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //Track the order
        try {
            $result=$exchanges->getPlatform()->order()->get([
                'orderId'=>'xxxxxxxx'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //Cancel an existing order
        try {
            $result=$exchanges->getPlatform()->order()->delete([
                'orderId'=>'xxxxxxxx'
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