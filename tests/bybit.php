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

$exchanges=new Exchanges('bybit',$key,$secret);

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
            $exchanges->setPlatform('spot')->setVersion('v5');
            $result=$exchanges->trader()->buy([
                '_symbol'=>'BTCUSDT',
                '_number'=>'0.0001',
                //'_price'=>'100000',
                //'_price'=>'100',
                '_client_id'=>'xxxxx'.rand(10000,99999),
            ]);
            print_r($result);
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
