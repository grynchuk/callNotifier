<?php
namespace ssApp;


interface eventI{
  function attach($event, $handler);
  function trigger($event, $data);
}


class event implements eventI {
    private $events ;
   
    function __construct(){
        $this->events=[
            'message'=>[],
            'call'=>[]
        ];
    }
    
    function detach(){
        
    }
    
    function attach($event, $handler){
        if(!array_key_exists($event,$this->events )){
            throw new Exception ('Unallowed event');
        }
        $this->events[$event][]=$handler;
    }
    function trigger($event, $data){
        if(!array_key_exists($event,$this->events )){
            throw new Exception ('Unallowed event');
        }
        $e=$this->events[$event];
        for($i=0, $l=count($e);
            $i<$l;
            $i++){
           $e[$i]($data);      
        }
        
    }
}