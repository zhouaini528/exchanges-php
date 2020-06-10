<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/kraken-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['kraken']['key'];
$secret=$keysecret['kraken']['secret'];

$exchanges=new Exchanges('kraken',$key,$secret);

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
            $result=$exchanges->getPlatform()->market()->time();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->assets();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->assetPairs();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->depth([
                'pair'=>'XXBTZUSD',
                'count'=>10,
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    case 2:{
        //bargaining transaction
        try {
            $result=$exchanges->getPlatform()->userTrade()->addOrder([
                //'userref'=>'xxxxx'  //Custom ID
                'pair' => 'XXBTZUSD',
                'type' => 'buy',
                'ordertype' => 'limit',
                'price' => '7000',
                'volume' => '1.123'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //track the order
        try {
            $result=$exchanges->getPlatform()->user()->queryOrders([
                //'userref'=>'xxxxx'  //Custom ID
                'txid'=>'xxxxxx,xxxxxxx,xxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //cancellation of order
        try {
            $result=$exchanges->getPlatform()->userTrade()->cancelOrder([
                //'userref'=>'xxxxx'  //Custom ID
                'txid'=>'xxxxxx,xxxxxxx,xxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    //user
    case 3:{
        try {
            $result=$exchanges->getPlatform()->user()->balance();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->user()->tradeBalance();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->user()->openOrders();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->user()->queryOrders();
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