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
$key=$keysecret['bitfinex']['key'];
$secret=$keysecret['bitfinex']['secret'];

$exchanges=new Exchanges('bitfinex',$key,$secret);

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
        $result=$exchanges->getPlatform()->account()->postInfoUser();
        break;
    }
    
    case 2:{
        $result=$exchanges->getPlatform()->account()->postLoginsHist();
        break;
    }
    
    case 3:{
        $result=$exchanges->getPlatform()->account()->postAuditHist();
        break;
    }
    
    case 4:{
        try {
            $result=$exchanges->getPlatform()->order()->postSubmit([
                //'cid'=>'',
                'type'=>'LIMIT',
                'symbol'=>'tBTCUSD',
                'price'=>'5000',
                'amount'=>'0.01',//Amount of order (positive for buy, negative for sell)
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        sleep(1);
        
        try {
            $result=$exchanges->getPlatform()->order()->post([
                //'cid'=>'',
                'symbol'=>'tBTCUSD',
                'id'=>['33950998275']
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        try {
            $result=$exchanges->getPlatform()->order()->postUpdate([
                //'cid'=>'',
                'symbol'=>'tBTCUSD',
                'id'=>'33950998275',
                'amount'=>0.02,
                'price'=>6000,
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        sleep(1);
        
        try {
            $result=$exchanges->getPlatform()->order()->postUpdate([
                //'cid'=>'',
                'id'=>'33950998275',
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
print_r($result);