<?php

session_start();

function db_query($findArray) {
    try {

        $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $query = new MongoDB\Driver\Query($findArray); 
         
        $rows = $mng->executeQuery("testdb.cars", $query);
        
        // foreach ($rows as $row) {
        //     echo '<pre>';
        //     var_dump($row);
        //     echo '</pre>';
        // }
        
        return $rows;

    } catch (MongoDB\Driver\Exception\Exception $e) {
    
        $filename = basename(__FILE__);
        $error_msg = "The ". $filename . "script has experienced an error.\nIt failed with the following exception:\nException:" . $e->getMessage() . "\nIn file:" . $e->getFile() . "\nOn line:" . $e->getLine() . "\n";       
    
        $status = 'error';
        $output = array('status' => $status, 'data' => $error_msg);
        return $output;

    }
}

function db_count($query) {
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

	$command = new MongoDB\Driver\Command(["count" => "cars", "query" => $query]);
	try {
	    $result = $mng->executeCommand("testdb", $command);
	    $res = current($result->toArray());
	    $count = $res->n;
	    return $count;
	} catch (MongoDB\Driver\Exception\Exception $e) {
	    die($e->getMessage());
	}
}

class Session {
    private $userId;

    public function __construct() {
        $this->userId = NULL;
    }
    
    public function isLoggedIn() {
        return ($this->userId != NULL);
    }

    public function login($userMail, $password) {
        if ($this->isLoggedIn()) {
            echo "already logged in";
            return false;
        } else {
            if (db_count(['email' => $userMail])) {
                $hashedPassword = sha1($password);
    
                if (db_count(['password' => $hashedPassword])) {
                    $rows = db_query(['email' => $userMail, 'password' => $hashedPassword]);

                    foreach ($rows as $row) {
                        echo '<pre>';
                        var_dump($row);
                        echo '</pre>';
                    }
                    $this->userId = (string)$rows[0]->_id;
                } else {
                    echo "password invalid";
                }
            } else {
                echo "username invalid!";
            }

        }
    }


    public function logout() {
        $this->userId = NULL;
    }

}