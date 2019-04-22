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
        
    }
    
    /**
     *
     * */
    function get(array $data=[]){
        try {
            $map=$this->map->request_account()->get($data);
            $result=$this->platform->account()->get($map);
            return $this->map->response_account()->get(['result'=>$result,'request'=>$data]);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}