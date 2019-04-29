### Introduction

The SDK brings together the apis of the most heavily traded exchanges, allowing developers to focus only on the business layer.It currently supports simple buying, selling, and querying, with more apis coming later.If you have special needs, you can use this method [getPlatform()](https://github.com/zhouaini528/exchanges-php/blob/master/README.md#the-original-object) alone to return an instance and call the underlying API

The SDK supports both uniform and native parameters.It is recommended that users use uniform parameters, and native parameters can be used for special requirements.

All submitted parameters and return as long as the first character for the underlined ` _ ` all for custom parameters.

Many interfaces are not yet complete, and users can continue to extend them based on my design. Welcome to improve it with me

[中文文档](https://github.com/zhouaini528/exchanges-php/blob/master/README_CN.md)

### Other exchanges API

[Bitmex](https://github.com/zhouaini528/bitmex-php)

[Okex](https://github.com/zhouaini528/okex-php)

[Huobi](https://github.com/zhouaini528/huobi-php)

[Binance](https://github.com/zhouaini528/binance-php)

[Exchanges](https://github.com/zhouaini528/exchanges-php) All integration

#### Install
```
composer require linwj/exchanges:dev-master

If something goes wrong add composer.json "minimum-stability":"dev"
```

#### More Tests
[Bitmex](https://github.com/zhouaini528/exchanges-php/tree/master/tests/bitmex.php)

[Binance](https://github.com/zhouaini528/exchanges-php/tree/master/tests/binance.php)

[Huobi](https://github.com/zhouaini528/exchanges-php/tree/master/tests/huobi.php)

[Okex](https://github.com/zhouaini528/exchanges-php/tree/master/tests/okex.php)


#### Exchanges initialization
```php
$exchanges=new Exchanges('binance',$key,$secret);
$exchanges=new Exchanges('bitmex',$key,$secret);
$exchanges=new Exchanges('okex',$key,$secret,$passphrase,$host);
$exchanges=new Exchanges('huobi',$key,$secret,$account_id,$host);
```
[Get huobi $account_id](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L28)

#### Uniform parameter return
```php
/**
 * Buy()   Sell()   Show() Uniform parameter return
 * @return [
 *      ***Return to original data
 *      ...
 *      ...
 *      ***Returns custom data in a uniform return parameter format with '_' underscore
 *      _status=>NEW  PART_FILLED  FILLED  CANCELING  CANCELLED  FAILURE
 *      _filled_qty=>Number of transactions completed
 *      _price_avg=>Average transaction price
 *      _order_id=>system ID
 *      _client_id=>custom ID
 * ]
 *
 * */
 
 /**
 * System error
 * http request code 400 403 500 503
 * @return [
 *      _error=>[
 *          ***Return to original data
 *          ...
 *          ...
 *          ***Returns custom data in a uniform return parameter format with '_' underscore
 *          _method => POST
 *          _url => https://testnet.bitmex.com/api/v1/order
 *          _httpcode => 400
 *      ]
 * ]
 * */
```
Buy and sell query uniform parameter return [detail](https://github.com/zhouaini528/exchanges-php/blob/master/src/Api/Trader.php#L59)

System error unified parameter return [binance](https://github.com/zhouaini528/exchanges-php/blob/master/tests/binance.php#L33)
[okex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L35)
[huobi](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L35)
[bitmex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/bitmex.php#L35)


#### Spot Trader
##### Market
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
]);
//The original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'MARKET',
    'quantity'=>'0.01',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_price'=>'10',
]);
//The original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'type'=>'market',
    'notional'=>'10'
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_price'=>'10',
]);
//The original parameters
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-market',
    'amount'=>10
]);

```
##### Limit
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
    '_price'=>'2000',
]); 
//The original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//The original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'price'=>'100',
    'size'=>'0.001',
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//The original parameters
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-limit',
    'amount'=>'0.001',
    'price'=>'2001',
]);
```
#### Future Trader
##### Market
```php
//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
]);
//The original parameters
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'orderQty'=>'1',
    'ordType'=>'Market',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_entry'=>true,//open long
]);
//The original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    //'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 1,
    'order_type'=>0,
]);
```
##### Limit
```php
//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
    '_price'=>100
]);
//The original parameters
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'price'=>'100',
    'orderQty'=>'1',
    'ordType'=>'Limit',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_price'=>'2000',
    '_entry'=>true,//open long
]);
//The original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 0,
    'order_type'=>0,
]);
```

#### The original object
```php
//binance
$exchanges->getPlatform()->trade()->postOrder([
    'symbol'=>'BTCUSDT',
    'side'=>'BUY',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);


//bitmex
$exchanges->getPlatform()->order()->post([
    'symbol'=>'XBTUSD',
    'price'=>'100',
    'side'=>'Buy',
    'orderQty'=>'1',
    'ordType'=>'Limit',
]);


//okex
$exchanges->getPlatform('spot')->order()->post([
    'instrument_id'=>'btc-usdt',
    'side'=>'buy',
    'price'=>'100',
    'size'=>'0.001',
    //'type'=>'market',
    //'notional'=>'100'
]);
$exchanges->getPlatform('future')->order()->post([
    'instrument_id'=>'btc-usd-190628',
    'type'=>'1',
    'price'=>'100',
    'size'=>'1',
]);


//huobi
$exchanges->getPlatform('spot')->order()->postPlace([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-limit',
    'amount'=>'0.001',
    'price'=>'100',
]);

$exchanges->getPlatform('future')->contract()->postOrder([
    'symbol'=>'BTC',//string    false   "BTC","ETH"...
    'contract_type'=>'quarter',//   string  false   Contract Type ("this_week": "next_week": "quarter":)
    'contract_code'=>'BTC190628',// string  false   BTC180914
    'price'=>'100',//   decimal true    Price
    'volume'=>'1',//    long    true    Numbers of orders (amount)
    'direction'=>'buy',//   string  true    Transaction direction
    'offset'=>'open',// string  true    "open", "close"
    //'client_order_id'=>'',//long  false   Clients fill and maintain themselves, and this time must be greater than last time
    //lever_rate    int true    Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
    //order_price_type   string true    "limit", "opponent"
]);

```

[More Tests](https://github.com/zhouaini528/exchanges-php/tree/master/tests)

[More API](https://github.com/zhouaini528/exchanges-php/tree/master/src/Api)


