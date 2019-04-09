### It is recommended that you use the test server first

Online interface testing[https://www.bitmex.com/api/explorer/](https://www.bitmex.com/api/explorer/)

Address of the test[https://testnet.bitmex.com](https://testnet.bitmex.com)

The official address[https://www.bitmex.com](https://www.bitmex.com)

All interface methods are initialized the same as those provided by bitmex. See details[src/api](https://github.com/zhouaini528/bitmex-php/tree/master/src/Api)

Many interfaces are not yet complete, and users can continue to extend them based on my design. Feel free to iterate with me.

[中文文档](https://github.com/zhouaini528/bitmex-php/blob/master/README_CN.md)

### Other exchanges API

[Bitmex](https://github.com/zhouaini528/bitmex-php)

[Okex](https://github.com/zhouaini528/okex-php)

[Huobi](https://github.com/zhouaini528/huobi-php)

[Binance](https://github.com/zhouaini528/binance-php)

#### Installation
```
composer require "linwj/bitmex dev-master"
```

Book Data [More](https://github.com/zhouaini528/bitmex-php/blob/master/tests/position.php)
```php
//Get market data
//Book data may be key and secret
try {
    $bitmex=new Bitmex();
    $result=$bitmex->orderBook()->get([
        'symbol'=>'ETHUSD',
        'depth'=>20
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}
```

Order [More](https://github.com/zhouaini528/bitmex-php/blob/master/tests/order.php)
```php
//Test API address  default  https://www.bitmex.com
$key='eLB_l505a_cuZL8Cmu5uo7EP';
$secret='wG3ndMquAPl6c-jHUQNhyBQJKGBwdFenIF2QxcgNKE_g8Kz3';
$host='https://testnet.bitmex.com';

$bitmex=new Bitmex($key,$secret,$host);

//bargaining transaction
try {
    $result=$bitmex->order()->post([
        'symbol'=>'XBTUSD',
        'price'=>'100',
        'side'=>'Buy',
        'orderQty'=>'1',
        'ordType'=>'Limit',
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}

//track the order
try {
    $result=$bitmex->order()->getOne([
        'symbol'=>'XBTUSD',
        'orderID'=>$result['orderID'],
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}

//update the order
try {
    $result=$bitmex->order()->put([
        'symbol'=>'XBTUSD',
        'orderID'=>$result['orderID'],
        'price'=>'200',
        'orderQty'=>'2',
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}

//cancellation of order
try {
    $result=$bitmex->order()->delete([
        'symbol'=>'XBTUSD',
        'orderID'=>$result['orderID'],
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}
```


Postion [More](https://github.com/zhouaini528/bitmex-php/blob/master/tests/position.php)
```php
//bargaining transaction
try {
    $bitmex=new Bitmex($key,$secret,$host);
    $result=$bitmex->position()->get([
        'symbol'=>'XBTUSD',
    ]);
    print_r($result);
}catch (\Exception $e){
    print_r(json_decode($e->getMessage(),true));
}
```

[More use cases](https://github.com/zhouaini528/bitmex-php/tree/master/tests)

[More API](https://github.com/zhouaini528/bitmex-php/tree/master/src/Api)


