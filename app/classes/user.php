<?php

namespace ssApp;

interface userI {
    
}

class user implements userI{
    
    private $id,
            $pages,
            $activeConn,
            $activeURL;
    
    
    public static function factory($par){
//        var_dump($par);
//        die('ee');
        $users=  container::getUsers();
        $instance='';
        if( array_key_exists($par['id'], $users ) ){
            $instance= container::getUsers()[$par['id']];                        
            $instance->setActivePage($par['url'], $par['conn']);
        }else{            
            $instance=new user($par) ;
            container::setUser($par['id'], $instance);
        }        
        return $instance;
    }
    
    
    public function __construct($par) {                
        $this->id=$par['id'];    
        $this->setActivePage($par['url'], $par['conn']);
    }     
   
    public function notify($data){
       $data=  json_encode($data);
       $this->activeConn->send($data);
    }   
    
    
    public function setActivePage( $url, $conn){
        $this->pages[ $url ] = $conn;        
        $this->activeConn = $conn;
        $this->activeURL=$url;
    }  
        
    public function __get($name) {            
        return $this->$name;
    }
    
    
    
}




