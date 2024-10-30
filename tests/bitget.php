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
$key=$keysecret['bitget']['key'];
$secret=$keysecret['bitget']['secret'];
$passphrase=$keysecret['bitget']['passphrase'];

$exchanges=new Exchanges('bitget',$key,$secret,$passphrase);

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
    case 2001:{
        $result=$exchanges->getPlatform('spot')->account()->getAssets();
        print_r($result);
        break;
    }


    default:{
        echo 'nothing';
        //exit;
    }
}
