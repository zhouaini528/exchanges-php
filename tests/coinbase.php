<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/coinbase-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['coinbase']['key'];
$secret=$keysecret['coinbase']['secret'];
$passphrase=$keysecret['coinbase']['passphrase'];
$host=$keysecret['coinbase']['host'];

$exchanges=new Exchanges('coinbase',$key,$secret,$passphrase,$host);

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
    //account
    case 1:{
        try {
            $result=$exchanges->getPlatform()->account()->getList();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->account()->get([
                'account_id'=>'c74a36f5-4f2b-495b-be29-6eb2458d1b3a'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->account()->getHolds([
                'account_id'=>'c74a36f5-4f2b-495b-be29-6eb2458d1b3a'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform()->account()->getLedger([
                'account_id'=>'c74a36f5-4f2b-495b-be29-6eb2458d1b3a'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    case 2:{
        try {
            $result=$exchanges->getPlatform()->order()->post([
                //'client_oid'=>'',
                'type'=>'limit',
                'side'=>'sell',
                'product_id'=>'BTC-USD',
                'price'=>'20000',
                'size'=>'0.01'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        //track the order
        try {
            $result=$exchanges->getPlatform()->order()->get([
                'id'=>$result['id'],
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        //cancellation of order
        try {
            $result=$exchanges->getPlatform()->order()->delete([
                'id'=>$result['id'],
                //'id'=>'6bad6a7d-b01a-4a93-9e6e-e9934bcef4ef',
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