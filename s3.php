<?php
/**
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    $socket->send(json_encode([
	 'caller'=> 26747
    ,'manager'=>90002 	
	,'type'=>'php' 			
	]));
  
 */

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ssApp\ei_server;
use ssApp\event;
use ssApp\user;



 include_once '../ei_usefull_class.php';
 require 'app/autoLoader.php';
 require '../composer/vendor/autoload.php';

 
 
 
    $loop   = React\EventLoop\Factory::create();
    //$pusher = new ssApp\Pusher;
    $se=new event();
    $ss=new ei_server($se);
    // Listen for the web server to make a ZeroMQ push after an ajax request
    $context = new React\ZMQ\Context($loop);
    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
    $pull->on('message', array($ss, 'onBlogEntry'));
    
    // Set up our WebSocket server for clients wanting real-time updates
    
    $webSock = new React\Socket\Server($loop);
    $webSock->listen(8081, '10.0.1.5'); // Binding to 0.0.0.0 means remotes can connect
    $webServer = new Ratchet\Server\IoServer(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(
                      $ss
            )
        ),
        $webSock
    );

    $se->attach('message', function(  $data ){

         
                    $user=user::factory(array(
                         'id'=> $data['msg']["data"]['user']
                       , 'url'=> $data['msg']["data"]['url']     
                       , 'conn'=> $data['conn']     
                    )); 
                    
                    $user=\ssApp\container::getUsers();
                    $d=[];
                    foreach($user as $us){                         
                         $d[$us->id]=$us->activeURL;  
                    }
                    foreach($user as $us){                         
                        $us->notify($d); 
                    }
                    
                                        
                    usfull::show_arr($d);            

 });
    
 $se->attach('call', function(  $data ){
     
     $user=\ssApp\container::getUsers()[$data['manager']];
     
     
     $user->notify([
         'type'=>'call'
        ,'orgId'=>$data['caller']
     ]);
     
     //usfull::show_arr($data);        
 });
 
 
    
    $loop->run();

