<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Interfaces;

/**
 * 行情接口
 * */
interface MarketInterface extends BaseInterface
{
    /**
     *
     * */
    function depth(array $data);
}