<?php
class a{
    function __construct(){
        echo 'class a';
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
    function __construct(){
        echo 'class b';
    }
    
    function a(){
        return new a();
    }
}

class c{
    function __construct(){
        echo 'class c';
    }
    
    function b(){
        return new b();
    }
}


$c=new c();
$c->b()->a()->a1();

$c->b()->a()->a2();

$c->b()->a()->a3();