<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/zb-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['zb']['key'];
$secret=$keysecret['zb']['secret'];

$exchanges=new Exchanges('zb',$key,$secret);

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
            $result=$exchanges->getPlatform()->account()->getSubUserList();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    case 2:{
        try {
            $result=$exchanges->getPlatform()->margin()->getLeverAssetsInfo();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->margin()->getLeverBills([
                'coin'=>'btc',
                'dataType'=>0,
                'pageIndex'=>0,
                'pageSize'=>10
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    case 3:{
        try {
            $result=$exchanges->getPlatform()->market()->getAllTicker();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getTicker([
                'market'=>'btc_usdt'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getDepth([
                'market'=>'btc_usdt',
                'size'=>'5'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getTrades([
                'market'=>'btc_usdt',
                'since'=>'xxxxxxxxx'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->market()->getKline([
                'market'=>'btc_usdt',
                //'type'=>'1day',
                'size'=>10
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        break;
    }
    
    case 4:{
        try {
            $result=$exchanges->getPlatform()->trade()->order([
                //'customerOrderId'=>'',
                'tradeType'=>'0',//1=buy,0=sell
                'currency'=>'btc_usdt',
                'price'=>'11000',
                'amount'=>'0.01',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $order=$exchanges->getPlatform()->trade()->getOrder([
                //'customerOrderId'=>'',
                'id'=>$result['id'],
                'currency'=>'btc_usdt',
            ]);
            print_r($order);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->trade()->cancelOrder([
                //'customerOrderId'=>'',
                'id'=>$result['id'],
                'currency'=>'btc_usdt',
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