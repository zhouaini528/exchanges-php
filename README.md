### Introduction
This SDK brings together the APIs of the many exchanges currently trading the most, allowing developers to focus only on the business layer. It is based on [Bitmex](https://github.com/zhouaini528/bitmex-php) [Okex](https://github.com/zhouaini528/okex-php) [Huobi](https://github.com/zhouaini528/huobi-php) [Binance](https://github.com/zhouaini528/binance-php) and so on, and these underlying APIs are encapsulated again. Its advantages support multiple platforms at the same time, support unified parameter input and output, also support native parameter input, and simple quantitative trading to fully meet your needs. Even if you have special requirements, you can use the method [getPlatform()](https://github.com/zhouaini528/exchanges-php/blob/master/README.md#the-original-object) to return the instance and call the underlying API.

[中文文档](https://github.com/zhouaini528/exchanges-php/blob/master/README_CN.md)

### Other exchanges API

[Exchanges](https://github.com/zhouaini528/exchanges-php) It includes all of the following exchanges and is highly recommended.

[Bitmex](https://github.com/zhouaini528/bitmex-php)

[Okex](https://github.com/zhouaini528/okex-php)

[Huobi](https://github.com/zhouaini528/huobi-php)

[Binance](https://github.com/zhouaini528/binance-php)

[Kucoin](https://github.com/zhouaini528/kucoin-php)

[Mxc](https://github.com/zhouaini528/Mxc-php)

[Coinbase](https://github.com/zhouaini528/coinbase-php)

[ZB](https://github.com/zhouaini528/zb-php)

[Bitfinex](https://github.com/zhouaini528/bitfinex-php)

#### Install
```
composer require linwj/exchanges
```

#### Exchanges initialization
```php
//Public
$exchanges=new Exchanges('binance');
$exchanges=new Exchanges('bitmex');
$exchanges=new Exchanges('okex');
$exchanges=new Exchanges('huobi');
$exchanges=new Exchanges('kucoin');
...
...

//Private
$exchanges=new Exchanges('binance',$key,$secret);
$exchanges=new Exchanges('bitmex',$key,$secret);
$exchanges=new Exchanges('okex',$key,$secret,$passphrase);
$exchanges=new Exchanges('huobi',$key,$secret,$account_id);
$exchanges=new Exchanges('kucoin',$key,$secret,$passphrase);
...
...
```
[Get Huobi Spot $account_id](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L101)

#### Uniform parameter return

All submitted parameters and return as long as the first character for the underlined ` _ ` all for custom parameters.

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
 *      _filed_amount=>transaction price
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
The Buy Sell method has a waiting time of [2 seconds](https://github.com/zhouaini528/exchanges-php/blob/master/src/Config/Exchanges.php) by default. Because the exchange is a matching transaction, the inquiry needs to wait. The default 2-second query can be turned off as：[buy($data,false)](https://github.com/zhouaini528/exchanges-php/blob/master/src/Api/Trader.php#L41)

Buy and sell query uniform parameter return [detail](https://github.com/zhouaini528/exchanges-php/blob/master/src/Api/Trader.php#L59)

System error unified parameter return [binance](https://github.com/zhouaini528/exchanges-php/blob/master/tests/binance.php#L33)
[okex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L35)
[huobi](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L35)
[bitmex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/bitmex.php#L35)
[kucoin](https://github.com/zhouaini528/exchanges-php/blob/master/tests/kucoin.php#L35)

The SDK currently only supports REST requests, and does not support Websocket for the time being. It will be added later.

Support for more request Settings [More](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L53)
```php
$exchanges->setOptions([
    //Set the request timeout to 60 seconds by default
    'timeout'=>10,
    
    //If you are developing locally and need an agent, you can set this
    'proxy'=>true,
    //More flexible Settings
    /* 'proxy'=>[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     ], */
    //Close the certificate
    //'verify'=>false,
]);
```

#### Spot Trader
##### Market
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
]);
//Support for original parameters
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
//Support for original parameters
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
//Support for original parameters
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
//Support for original parameters
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
//Support for original parameters
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
//Support for original parameters
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
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.001',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'quantity'=>'0.001',
    'type'=>'MARKET',
]);

//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
]);
//Support for original parameters
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
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    //'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 1,
    'order_type'=>0,
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'ETC191227',
    '_number'=>'1',
    '_entry'=>true,//true:open  false:close
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'XRP',//string false "BTC","ETH"...
    'contract_type'=>'quarter',//string false Contract Type ("this_week": "next_week": "quarter":)
    'contract_code'=>'XRP190927',//string false BTC180914
    //'price'=>'0.3',// decimal true Price
    'volume'=>'1',//long true Numbers of orders (amount)
    //'direction'=>'buy',// string  true    Transaction direction
    'offset'=>'open',// string  true    "open", "close"
    'order_price_type'=>'opponent',//"limit", "opponent"
    'lever_rate'=>20,//int true Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
]);
```
##### Limit
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.001',
    '_price'=>'6000'
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'quantity'=>'0.001',
    'type'=>'LIMIT',
    'price'=>'6500',
    'timeInForce'=>'GTC',
]);

//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
    '_price'=>100
]);
//Support for original parameters
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
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 0,
    'order_type'=>0,
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'XRP190927',
    '_number'=>'1',
    '_price'=>'0.3',
    '_entry'=>true,//true:open  false:close
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'XRP',//string false "BTC","ETH"...
    'contract_type'=>'quarter',//string false Contract Type ("this_week": "next_week": "quarter":)
    'contract_code'=>'XRP190927',// string  false   BTC180914
    'price'=>'0.3',//decimal true Price
    'volume'=>'1',//long true Numbers of orders (amount)
    //'direction'=>'buy',// string  true Transaction direction
    'offset'=>'open',// string  true    "open", "close"
    'order_price_type'=>'limit',//"limit", "opponent"
    'lever_rate'=>20,//int true Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
]);
```

#### Get Order Details
```php
//binance
$exchanges->trader()->show([
    '_symbol'=>'BTCUSDT',
    '_order_id'=>'324314658',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//bitmex
$exchanges->trader()->show([
    '_symbol'=>'XBTUSD',
    '_order_id'=>'7d03ac2a-b24d-f48c-95f4-2628e6411927',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//okex
$exchanges->trader()->show([
    '_symbol'=>'BTC-USDT',
    '_order_id'=>'2671215997495296',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
$exchanges->trader()->show([
    '_symbol'=>'BTC-USD-190927',
    '_order_id'=>'2671566274710528',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
$exchanges->trader()->show([
    '_symbol'=>'BTC-USD-SWAP',
    '_order_id'=>'2671566274710528',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//huobi spot
$exchanges->trader()->show([
    '_order_id'=>'29897313869',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
//huobi future
$exchanges->trader()->show([
    '_symbol'=>'XRP190927',
    '_order_id'=>'2715696586',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

```

#### Get accounts or positions
```php
//binance
//Get current account information.
$exchanges->account()->get();

//bitmex
//bargaining transaction
$exchanges->account()->get([
    //Default return all
    //'_symbol'=>'XBTUSD'
]);

//okex  spot
//This endpoint supports getting the balance, amount available/on hold of a token in spot account.
$exchanges->account()->get([
    '_symbol'=>'BTC',
]);

//okex future
//Get the information of holding positions of a contract.
$exchanges->account()->get([
    '_symbol'=>'BTC-USD-190628',
]);

//okex swap
$exchanges->account()->get([
    '_symbol'=>'BTC-USD-SWAP',
]);

//huobi spot
$exchanges->account()->get([
    '_symbol'=>'btcusdt',
]);

//huobi future
$exchanges->account()->get([
    '_symbol'=>'BTC190927',
]);
```

#### Support for original parameters
Below is the call to the underlying API to initiate a new order instance
```php
//binance
$exchanges->getPlatform('spot')->trade()->postOrder([
    'symbol'=>'BTCUSDT',
    'side'=>'BUY',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);
$exchanges->getPlatform('future')->trade()->postOrder([
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
$result=$exchanges->getPlatform('swap')->order()->post([
    'instrument_id'=>'BTC-USD-SWAP',
    'type'=>'1',
    'price'=>'5000',
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
    'symbol'=>'XRP',//string    false   "BTC","ETH"...
    'contract_type'=>'quarter',//   string  false   Contract Type ("this_week": "next_week": "quarter":)
    'contract_code'=>'XRP190927',// string  false   BTC180914
    'price'=>'0.3',//   decimal true    Price
    'volume'=>'1',//    long    true    Numbers of orders (amount)
    'direction'=>'buy',//   string  true    Transaction direction
    'offset'=>'open',// string  true    "open", "close"
    'order_price_type'=>'limit',//"limit", "opponent"
    'lever_rate'=>20,//int  true    Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
    //'client_order_id'=>'',//long  false   Clients fill and maintain themselves, and this time must be greater than last time
]);

```

[More Tests](https://github.com/zhouaini528/exchanges-php/tree/master/tests)

[More API](https://github.com/zhouaini528/exchanges-php/tree/master/src/Api)
