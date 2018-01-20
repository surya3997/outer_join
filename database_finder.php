<?php

try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $query = new MongoDB\Driver\Query(["name" => "Guru"]); 
     
    $rows = $mng->executeQuery("testdb.cars", $query);
    $printer = array();
    foreach ($rows as $row) {
    	array_push($printer, $row);
    }

	//die(json_encode($printer[0]));
	echo (string)$printer[0]->_id;
    
} catch (MongoDB\Driver\Exception\Exception $e) {

    $filename = basename(__FILE__);
    
    echo "The $filename script has experienced an error.\n"; 
    echo "It failed with the following exception:\n";
    
    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";       
}

?>
