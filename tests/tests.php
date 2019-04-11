<?php
class a{
    function __construct(){
        echo 'class a __construct'."\n";
    }
    
    function a1(){
        echo 'a1'."\n";
    }
    
    function a2(){
        echo 'a2'."\n";
    }
    
    function a3(){
        echo 'a3'."\n";
    }
}

class b{
    protected $cache;
    
    function __construct(){
        echo 'class b __construct'."\n";
    }
    
    function b1($tags='tags'){
        return new a();
    }
    
    function b2($tags='tags'){
        static $cache=[];
        if(isset($cache[$tags])) return $cache[$tags];
        return $cache[$tags]=new a();
    }
    
    function b3($tags='tags'){
        if(isset($this->cache[$tags])) return $this->cache[$tags];
        return $this->cache[$tags]=new a();
    }
}

/*内存计算*/
$start = memory_get_usage();
$b=new b();
$b->b1()->a1();
$b->b1()->a2();
$b->b1()->a3();
/*内存计算*/
$end = memory_get_usage();
echo ($end-$start)."\n";

/*内存计算*/
$start = memory_get_usage();
$b=new b();
$b->b2()->a1();
$b->b2()->a2();
$b->b2()->a3();
/*内存计算*/
$end = memory_get_usage();
echo ($end-$start)."\n";

/*内存计算*/
$start = memory_get_usage();
$b=new b();
$b->b3()->a1();
$b->b3()->a2();
$b->b3()->a3();
/*内存计算*/
$end = memory_get_usage();
echo ($end-$start)."\n";



