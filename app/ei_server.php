<?php
namespace ssApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ei_server implements MessageComponentInterface {
   protected $clients
              ;
     
   
   
    public function __construct($e) {
        $this->eiEvent= $e;
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {        
        $this->clients->attach($conn);
      //  echo "New connection! ({$conn->resourceId})\n";
        
    }

    
    
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);
        echo 'trigger Call';
        $this->eiEvent->trigger( 'call', array(
           'caller'=>$entryData[ 'caller'],
           'manager'=>$entryData[ 'manager'] 
        ) ); 
        
    }

    public function onMessage(ConnectionInterface $from, $msg) {
       // echo " $msg \n "; 
        $msg=json_decode($msg,true);
        if(!$msg){ throw new Exception(' message is not recognized '); }
        $resourceId=$from->resourceId;
        //var_dump($msg);
        //die();
        
        $this->eiEvent->trigger( 'message', array(
           'msg'=>$msg,
           'conn'=>$from, 
           'type'=> $msg['type']     
        ) ); 
        
        
//        $numRecv = count($this->clients) - 1;
//        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
//        }
        
        
    }

    
    
    
    public function onClose(ConnectionInterface $conn) {
//        // The connection is closed, remove it, as we can no longer send it messages
          $this->clients->detach($conn);
          echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
    
    
    
    
}