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
$key=$keysecret['gate']['key'];
$secret=$keysecret['gate']['secret'];

$exchanges=new Exchanges('gate',$key,$secret,'','https://api.gateio.ws/a/');


//Support for more request Settings
$exchanges->setOptions([
    //Set the request timeout to 60 seconds by default
    'timeout'=>10,
    'curl'=>[
        CURLOPT_PROXY => 'proxy.local',
        CURLOPT_PROXYPORT => '10808',
        CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5,
    ]
]);

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //****************************delivery
    //******market
    case 1:{
        try {
            $result=$exchanges->getPlatform('delivery')->market()->getTickers(['settle'=>'usdt']);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('delivery')->market()->getOrderBook([
                'settle'=>'usdt',
                'contract'=>'BTC_USDT_WEEKLY_'.date('Ymd',time())
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('delivery')->market()->getTrades([
                'settle'=>'usdt',
                'contract'=>'BTC_USDT_WEEKLY_'.date('Ymd',time())
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('delivery')->market()->getCandlesticks([
                'settle'=>'usdt',
                'contract'=>'BTC_USDT_WEEKLY_'.date('Ymd',time())
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    //******order
    case 2:{
        //bargaining transaction
        try {
            $result=$exchanges->getPlatform('delivery')->order()->post([
                //'text'=>'t-xxxxxxxxxx',//custom ID
                'settle'=>'btc',
                'contract'=>'BTC_USD',
                'size'=>'1',
                'price'=>'4000',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //track the order
        try {
            $result=$exchanges->getPlatform('delivery')->order()->get([
                'settle'=>'btc',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //cancellation of order
        try {
            $result=$exchanges->getPlatform('delivery')->order()->delete([
                'settle'=>'btc',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    //****************************spot
    //******market
    case 3:{
        try {
            $result=$exchanges->getPlatform('spot')->market()->getTickers();
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('spot')->market()->getOrderBook([
                'currency_pair'=>'BTC_USDT'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('spot')->market()->getTrades([
                'currency_pair'=>'BTC_USDT'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('spot')->market()->getCandlesticks([
                'currency_pair'=>'BTC_USDT'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    //******order
    case 4:{
        //bargaining transaction
        try {
            $result=$exchanges->getPlatform('spot')->order()->post([
                //'text'=>'t-xxxxxxxxxx',//custom ID
                'currency_pair'=>'BTC_USDT',
                'type'=>'limit',
                'side'=>'buy',
                'amount'=>'0.1',
                'price'=>'4000',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //track the order
        try {
            $result=$exchanges->getPlatform('spot')->order()->get([
                'currency_pair'=>'BTC_USDT',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //cancellation of order
        try {
            $result=$exchanges->getPlatform('spot')->order()->delete([
                'currency_pair'=>'BTC_USDT',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    
    //****************************future
    //******market
    case 5:{
        try {
            $result=$exchanges->getPlatform('future')->market()->getTickers(['settle'=>'btc']);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('future')->market()->getOrderBook([
                'settle'=>'btc',
                'contract'=>'BTC_USD'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('future')->market()->getTrades([
                'settle'=>'btc',
                'contract'=>'BTC_USD'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        try {
            $result=$exchanges->getPlatform('future')->market()->getCandlesticks([
                'settle'=>'btc',
                'contract'=>'BTC_USD'
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }
    case 6:{
        //bargaining transaction
        try {
            $result=$exchanges->getPlatform('future')->order()->post([
                //'text'=>'t-xxxxxxxxxx',//custom ID
                'settle'=>'btc',
                'contract'=>'BTC_USD',
                'size'=>'1',
                'price'=>'4000',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //track the order
        try {
            $result=$exchanges->getPlatform('future')->order()->get([
                'settle'=>'btc',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        
        //cancellation of order
        try {
            $result=$exchanges->getPlatform('future')->order()->delete([
                'settle'=>'btc',
                'order_id'=>'xxxxxxxxxx',
            ]);
            print_r($result);
        }catch (\Exception $e){
            print_r(json_decode($e->getMessage(),true));
        }
        break;
    }

    case 1001:{
        try {
            $result=$exchanges->getPlatform()->account()->get();
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