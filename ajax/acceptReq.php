<?php

    include_once "../includes/session.php";

    $session = new Session();

    $jsonMessage = array();

    if (!$session->isLoggedIn()) {
        $jsonMessage['status'] = 'Illegal_entry';
        die(json_encode($jsonMessage));
    }

    $id = (string)$_SESSION['id'];
    $accept = (string)$_POST['accept_id'];
    $decision = $_POST['decision'];

    if ($id === $accept) {
        die("same user id");
    }

    $mongo_id = new MongoDB\BSON\ObjectID($id);
    $mongo_accept_id = new MongoDB\BSON\ObjectID($accept);

    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

    if ($decision == 'accept') {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_id, 'friends.' . $accept => 'gave request'],
            ['$set' => ['friends.$.' . $accept => "friends"]],
            ['multi' => true, 'upsert' => false]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_accept_id, 'friends.' . $id => 'waiting for response'],
            ['$set' => ['friends.$.' . $id => "friends"]],
            ['multi' => true, 'upsert' => false]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);
    } else if ($decision == 'reject') {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_id, 'friends.' . $accept => 'gave request'],
            ['$unset' => ['friends.$' => ""]],
            ['multi' => true, 'upsert' => false]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_accept_id, 'friends.' . $id => 'waiting for response'],
            ['$unset' => ['friends.$' => ""]],
            ['multi' => true, 'upsert' => false]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_id],
            ['$pull' => ['friends' => NULL]]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => $mongo_accept_id],
            ['$pull' => ['friends' => NULL]]
        );
        $result = $manager->executeBulkWrite('testdb.cars', $bulk);

    } else {
        echo "Invalid request";
    }
    
    echo "updation done";

?>