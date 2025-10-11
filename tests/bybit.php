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
$key=$keysecret['bybit']['key'];
$secret=$keysecret['bybit']['secret'];

$exchanges=new Exchanges('bybit',$key,$secret,'https://api-testnet.bybit.com');

//Support for more request Settings
$exchanges->setOptions([
    //Set the request timeout to 60 seconds by default
    //'timeout'=>10,

]);

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){

    case 1:{
        try {
            //spot inverse linear

            //$exchanges->setPlatform('spot')->setVersion('v5');
            $result=$exchanges->trader()->buy([
                '_symbol'=>'BTCUSDT',
                '_number'=>'0.0001',
                //'_price'=>'100000',
                '_price'=>'60000',
                '_client_id'=>'xxxxx'.rand(10000,99999),
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 2:{
        try {
            //spot inverse linear
            $exchanges->setPlatform('spot')->setVersion('v5');
            $cid='xxxxx'.rand(10000,99999);
            $result=$exchanges->trader()->sell([
                '_symbol'=>'BTCUSDT',
                '_number'=>'0.0001',
                '_price'=>'150000',
                //'_price'=>'20',
                '_client_id'=>$cid
            ]);
            print_r($result);

            echo '22222333333';
            sleep(2);

            $result=$exchanges->trader()->cancel([
                '_symbol'=>'BTCUSDT',
                //'_price'=>'20',
                '_client_id'=>$cid
            ]);
            print_r($result);


        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 3:{
        try {
            //spot inverse linear
            $exchanges->setPlatform('spot')->setVersion('v5');
            $cid='xxxxx'.rand(10000,99999);

            $exchanges->setOptions([
                'headers'=>[
                    //X-Referer or Referer - 經紀商用戶專用的頭參數
                    //X-BAPI-RECV-WINDOW 默認值為5000
                    //cdn-request-id
                    'X-BAPI-RECV-WINDOW'=>'6000',
                ],
                //'debug'=>true,
                //'host'=>'https://api-demo.bybit.com',
                //'aaa'=>'1',
            ]);
            $result=$exchanges->trader()->buy([
                '_symbol'=>'BTCUSDT',
                '_number'=>'0.0001',
                '_price'=>'100000',
                //'_price'=>'20',
                '_client_id'=>$cid
            ]);
            print_r($result);
            //die;
            echo '22222333333';
            sleep(2);

            $result=$exchanges->trader()->cancel([
                '_symbol'=>'BTCUSDT',
                //'_price'=>'20',
                '_client_id'=>$cid
            ]);
            print_r($result);


        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 33:{
        $result=$exchanges->getPlatform()->account()->getInfo();
        print_r($result);
        break;
    }


    case 88:{
        try {
            //spot inverse linear
            $exchanges->setPlatform('inverse');
            $cid='xxxxx'.rand(10000,99999);
            $result=$exchanges->trader()->sell([
                '_symbol'=>'BTCUSD',
                '_number'=>'100',
                //'_price'=>'150000',
                '_entry'=>false,

                '_client_id'=>$cid
            ]);
            print_r($result);

            die;
            echo '22222333333';
            sleep(2);

            $result=$exchanges->trader()->cancel([
                '_symbol'=>'BTCUSD',
                //'_price'=>'20',
                '_client_id'=>$cid
            ]);
            print_r($result);


        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }


    case 89:{
        try {
            //spot inverse linear
            $exchanges->setPlatform('inverse');
            $cid='xxxxx'.rand(10000,99999);
            $result=$exchanges->trader()->sell([
                '_symbol'=>'BTCUSD',
                '_number'=>'5',
                //'_price'=>'150000',
                '_entry'=>true,

                '_client_id'=>$cid
            ]);
            print_r($result);
            //die;
        }catch (\Exception $e){
            print_r($e->getMessage());
        }


        sleep(3);
        try {
            //spot inverse linear
            $exchanges->setPlatform('inverse');
            $cid='xxxxx'.rand(10000,99999);
            $result=$exchanges->trader()->buy([
                '_symbol'=>'BTCUSD',
                '_number'=>'5',
                //'_price'=>'150000',
                '_entry'=>false,

                '_client_id'=>$cid
            ]);
            print_r($result);
            //die;
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 899:{
        try {
            //spot inverse linear
            $exchanges->setPlatform('inverse');
            $cid='xxxxx'.rand(10000,99999);
            $result=$exchanges->trader()->show([
                '_symbol'=>'BTCUSD',
                '_client_id'=>$cid
            ]);
            print_r($result);
            //die;
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }



    case 2001:{
        $result=$exchanges->getPlatform()->position()->getList([
            'category'=>'linear',
            'symbol'=>'BTCUSDT',
        ]);
        print_r($result);
        break;
    }


    default:{
        echo 'nothing';
        //exit;
    }
}
