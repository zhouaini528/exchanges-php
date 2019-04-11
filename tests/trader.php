<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitmex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

$key='eLB_l505a_cuZL8Cmu5uo7EP';
$secret='wG3ndMquAPl6c-jHUQNhyBQJKGBwdFenIF2QxcgNKE_g8Kz3';
$host='https://testnet.bitmex.com';

$exchanges=new Exchanges('huobi',$key,$secret);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
]);

$exchanges->trader()->sell([
    '_symbol'=>'币种',
    '_number'=>'购买数量',
    
    //期货
    '_future'=>'是否现货与期货，0：现货   1：期货',
    '_entryclosed'=>'0:开仓   1:平仓',
    
    '_price'=>'当前价格    填写参数为：限价交易    不填写为：市价交易',
    '_client_id'=>'自定义ID',
    
    //'原生接口参数'
]);
exit;
$exchanges->account()->position([]);
die;



