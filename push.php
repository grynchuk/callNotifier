<?php

 $entryData = array(
        'category' => 'category'
      , 'title'    => 'title'
      , 'article'  => 'article'
      , 'when'     => time()
    );
/*
    $pdo->prepare("INSERT INTO blogs (title, article, category, published) VALUES (?, ?, ?, ?)")
        ->execute($entryData['title'], $entryData['article'], $entryData['category'], $entryData['when']);
*/
    // This is our new stuff
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://127.0.0.1:5555");

    $socket->send(json_encode($entryData));