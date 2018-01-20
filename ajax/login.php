<?php

    include_once "../includes/session.php";

    $session = new Session();

    $email = $_POST['email'];
    $passwd = $_POST['password'];

    $session->login($email, $passwd);

?>