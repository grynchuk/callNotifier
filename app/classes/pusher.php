<?php

namespace ssApp;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    protected $clients
              ;
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function __construct($e) {
        $this->eiEvent= $e;
        $this->clients = new \SplObjectStorage;
    }
    
    
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "  Subscribe \n ";
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);
         echo "  nBlogEntry \n ";
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];
        
        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

    
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
         $this->clients->attach($conn);
        echo "  onOpen \n ";
    }
    public function onClose(ConnectionInterface $conn) {
         $this->clients->detach($conn);
        echo "  onClose \n ";
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    //    echo "onCall";
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        echo "  onPublish \n ";
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
}
