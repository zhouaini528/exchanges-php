<?php
/**
 * @author lin <465382251@qq.com>
 * */

namespace Lin\Exchange\Api;

use Lin\Exchange\Interfaces\AccountInterface;

class Account extends Base implements AccountInterface
{
    /**
     *
     * */
    function position(array $data){
        $data=$this->map->account()->position($data);
        
        
        return $this->platform->account()->position($data);
    }
}