<?php

namespace ssApp;

class container{
    static public $users=[],
                  $userResourceId=[];
    
    
    public static function getUsers(){        
        return  self::$users;
    }
    
    
    public static function  setUser( $id , userI $user  ){
        self::$users[$id]=$user;        
    }
    
}



