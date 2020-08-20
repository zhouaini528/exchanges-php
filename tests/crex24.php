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
$key=$keysecret['crex']['key'];
$secret=$keysecret['crex']['secret'];

$exchanges=new Exchanges('crex',$key,$secret);

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
            $result=$exchanges->getPlatform()->market()->getCurrencies();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getInstruments();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getTickers();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getRecentTrades([
                'instrument'=>'LTC-BTC'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getOrderBook([
                'instrument'=>'LTC-BTC',
                'limit'=>10
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getOhlcv([
                'instrument'=>'LTC-BTC',
                'granularity'=>'30m'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getTradingFeeSchedules();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->market()->getWithdrawalFees([
                'currency'=>'LTC'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 2:{
        try {
            $result=$exchanges->getPlatform()->account()->getBalance([
                //'currency'=>'FREE'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->account()->getDepositAddress([
                'currency'=>'BTC'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->account()->getDepositAddress([
                'currency'=>'BTC'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->account()->getMoneyTransfers();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
        break;
    }

    case 3:{
        try {
            $result=$exchanges->getPlatform()->trading()->postPlaceOrder([
                'instrument'=>'ETH-BTC',
                'side'=>'buy',
                'type'=>'limit',
                'volume'=>'100',
                'price'=>'0.01',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->trading()->getOrderStatus([
                'id'=>'xxxxxxxxxx'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->trading()->getOrderTrades([
                'id'=>'xxxxxxxxxx'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->trading()->postCancelOrdersById([
                //'id'=>'xxxxxxxxxx'
                'id'=>['111111','22222222']
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->trading()->getOrderHistory();
            print_r($result);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }

        try {
            $result=$exchanges->getPlatform()->trading()->getTradeHistory();
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
