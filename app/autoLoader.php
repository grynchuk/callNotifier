<?php

spl_autoload_register(function($class){
   
    $class=explode("\\",$class);
    $class=$class[count($class)-1];
    
    $dir=[
         'app/'
        ,'app/classes/'
    ];
    
    for($i=0, $l=count($dir);
        $i<$l;
        $i++
       ){
            
            
         $file=$dir[$i].$class.'.php';             
//         echo $file;
//         echo "\n";
         if(file_exists( $file )){
             include_once $file; 
             break;
         }
       }    
});


