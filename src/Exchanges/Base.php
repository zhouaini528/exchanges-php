<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Bitmex;

use Lin\Bitmex\Api\Announcement;
use Lin\Bitmex\Api\ApiKey;
use Lin\Bitmex\Api\Chat;
use Lin\Bitmex\Api\Execution;
use Lin\Bitmex\Api\Funding;
use Lin\Bitmex\Api\GlobalNotification;
use Lin\Bitmex\Api\Instrument;
use Lin\Bitmex\Api\Insurance;
use Lin\Bitmex\Api\Leaderboard;
use Lin\Bitmex\Api\Liquidation;
use Lin\Bitmex\Api\Order;
use Lin\Bitmex\Api\OrderBook;
use Lin\Bitmex\Api\Position;
use Lin\Bitmex\Api\Quote;
use Lin\Bitmex\Api\Schema;
use Lin\Bitmex\Api\Settlement;
use Lin\Bitmex\Api\Stats;
use Lin\Bitmex\Api\User;
use Lin\Bitmex\Api\UserEvent;
use Lin\Bitmex\Api\Trade;

class Bitmex
{
    protected $key;
    protected $secret;
    protected $host;
    
    function __construct(string $key='',string $secret='',string $host='https://www.bitmex.com'){
        $this->key=$key;
        $this->secret=$secret;
        $this->host=$host;
    }
    
    /**
     * 
     * */
    private function init(){
        return [
            'key'=>$this->key,
            'secret'=>$this->secret,
            'host'=>$this->host,
        ];
    }
    
    /**
     * Public Announcements List Operations Expand Operations
     * */
    function announcement(){
        return new Announcement($this->init());
    }
    
    /**
     * Persistent API Keys for Developers List Operations Expand Operations
     * */
    function apiKey(){
        return new ApiKey($this->init());
    }
    
    /**
     * Trollbox Data List Operations Expand Operations
     * */
    function chat(){
        return new Chat($this->init());
    }
    
    /**
     * Raw Order and Balance Data List Operations Expand Operations
     * */
    function execution(){
        return new Execution($this->init());
    }
    
    /**
     * Swap Funding History List Operations Expand Operations
     * */
    function funding(){
        return new Funding($this->init());
    }
    
    /**
     * Account Notifications List Operations Expand Operations
     * */
    function globalNotification(){
        return new GlobalNotification($this->init());
    }
    
    /**
     * Tradeable Contracts, Indices, and History List Operations Expand Operations
     * */
    function instrument(){
        return new Instrument($this->init());
    }
    
    /**
     *  Insurance Fund Data List Operations Expand Operations
     * */
    function insurance(){
        return new Insurance($this->init());
    }
    
    /**
     *  Information on Top Users List Operations Expand Operations
     * */
    function leaderboard(){
        return new Leaderboard($this->init());
    }
    
    /**
     *Active Liquidations List Operations Expand Operations
     * */
    function liquidation(){
        return new Liquidation($this->init());
    }
    
    /**
     *Placement, Cancellation, Amending, and History List Operations Expand Operations
     * */
    function order(){
        return new Order($this->init());
    }
    
    /**
     *Level 2 Book Data List Operations Expand Operations
     * */
    function orderBook(){
        return new OrderBook($this->init());
    }
    
    /**
     *Summary of Open and Closed Positions List Operations Expand Operations
     * */
    function position(){
        return new Position($this->init());
    }
    
    /**
     *Best Bid/Offer Snapshots & Historical Bins List Operations Expand Operations
     * */
    function quote(){
        return new Quote($this->init());
    }
    
    /**
     *Dynamic Schemata for Developers List Operations Expand Operations
     * */
    function schema(){
        return new Schema($this->init());
    }
    
    /**
     *Historical Settlement Data List Operations Expand Operations
     * */
    function settlement(){
        return new Settlement($this->init());
    }
    
    /**
     *Exchange Statistics List Operations Expand Operations
     * */
    function stats(){
        return new Stats($this->init());
    }
    
    /**
     *Individual & Bucketed Trades List Operations Expand Operations
     * */
    function trade(){
        return new Trade($this->init());
    }
    
    /**
     *Account Operations List Operations Expand Operations
     * */
    function user(){
        return new User($this->init());
    }
    
    /**
     *User Events for auditing
     * */
    function userEvent(){
        return new UserEvent($this->init());
    }
}