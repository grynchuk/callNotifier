<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ssApp\ei_server;
use ssApp\event;
use ssApp\user;
include_once '../ei_usefull_class.php';
 require 'app/autoLoader.php';
 require '../composer/vendor/autoload.php';
 
 $se=new event();
 $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                   new ei_server($se)
            )
        ),
        8081 
    );
 
 $se->attach('message', function(  $data ){
//     $data['conn']='';
//     var_dump($data['msg']);
//        die('ee');
     $user=user::factory(array(
          'id'=> $data['msg']["data"]['user']
        , 'url'=> $data['msg']["data"]['url']     
        , 'conn'=> $data['conn']     
     )); 
     
     $all=\ssApp\container::getUsers();
     
     $data=[];
     foreach($all as $us){
             $data[$us->id]=$us->activeURL;         
     }
        
 });
 
 $server->run();
 
 
 
    
    
 
 
    
    