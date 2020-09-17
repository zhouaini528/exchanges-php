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
    //***********Inverse

    //Inverse publics
    case 1:{
        try {
            $result=$exchanges->getPlatform('bybitinverse')->publics()->getOrderBookL2([
                'symbol'=>'BTCUSD'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->publics()->getKlineList([
                'symbol'=>'BTCUSD',
                'interval'=>'15',
                'from'=>time()-3600,
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->publics()->getTickers();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->publics()->getTradingRecords([
                'symbol'=>'BTCUSD',
                'limit'=>'5',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->publics()->getSymbols();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }
    //Inverse order
    case 2:{
        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postOrderCreate([
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'side'=>'Buy',
                'symbol'=>'BTCUSD',
                'order_type'=>'Limit',
                'qty'=>'1',
                'price'=>'4000',
                'time_in_force'=>'GoodTillCancel',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getOrder([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postOrderReplace([
                'order_id'=>'xxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
                'p_r_qty'=>'2',
                'p_r_price'=>'4999'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postOrderCancel([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getOrderList([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    //Inverse stoporder
    case 3:{
        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postStopOrderCreate([
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'side'=>'Buy',
                'symbol'=>'BTCUSD',
                'order_type'=>'Limit',
                'qty'=>'1',
                'price'=>'4000',
                'time_in_force'=>'GoodTillCancel',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getStopOrder([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postStopOrderReplace([
                'order_id'=>'xxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
                'p_r_qty'=>'2',
                'p_r_price'=>'4999'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postStopOrderCancel([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getStopOrderList([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    //Inverse position
    case 4:{
        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getPositionList([
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postChangePositionMargin([
                'symbol'=>'BTCUSD',
                'margin'=>'1'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postPositionTradingStop([
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getUserLeverage();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->postUserLeverageSave([
                'symbol'=>'BTCUSD',
                'leverage'=>'1'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitinverse')->privates()->getExecutionList([
                'symbol'=>'BTCUSD',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    //***********Linear

    //Linear publics
    case 11:{
        try {
            $result=$exchanges->getPlatform('bybitlinear')->publics()->getOrderBookL2([
                'symbol'=>'BTCUSDT'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->publics()->getKline([
                'symbol'=>'BTCUSDT',
                'interval'=>'15',
                'from'=>time()-3600,
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->publics()->getTickers();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->publics()->getRecentTradingRecords([
                'symbol'=>'BTCUSDT',
                'limit'=>'5',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->publics()->getSymbols();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }
    //Linear order
    case 12:{
        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postOrderCreate([
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'side'=>'Buy',
                'symbol'=>'BTCUSDT',
                'order_type'=>'Limit',
                'qty'=>'1',
                'price'=>'4000',
                'time_in_force'=>'GoodTillCancel',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->getOrderSearch([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postOrderReplace([
                'order_id'=>'xxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
                'p_r_qty'=>'2',
                'p_r_price'=>'4999'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postOrderCancel([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->getOrderList([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    //Linear stoporder
    case 13:{
        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postStopOrderCreate([
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'side'=>'Buy',
                'symbol'=>'BTCUSDT',
                'order_type'=>'Limit',
                'qty'=>'1',
                'price'=>'4000',
                'time_in_force'=>'GoodTillCancel',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->getStopOrderSearch([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postStopOrderReplace([
                'order_id'=>'xxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
                'p_r_qty'=>'2',
                'p_r_price'=>'4999'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postStopOrderCancel([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->getStopOrderList([
                'order_id'=>'xxxxxxxxxxxxx',
                //'order_link_id'=>'xxxxxxxxxxxxxx',
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    //Linear position
    case 14:{
        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->getPositionList([
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform('bybitlinear')->privates()->postPositionTradingStop([
                'symbol'=>'BTCUSDT',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }


    default:{
        echo 'nothing';
        //exit;
    }
}
