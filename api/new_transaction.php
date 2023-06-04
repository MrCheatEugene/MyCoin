<?php
header('Content-Type: text/plain');
session_start();
include '../mailer.php';
include '../sql.php';
include '../misc.php';

if(empty($_SESSION['user']) or empty($_GET['rx_address']) or empty($_GET['amount']) or is_array(getuser($_GET['rx_address'])) ==false){
    http_response_code(404);
    die('An error occured.');
}else{
    $code=random_int(1000,99999);
    $tid=bin2hex(random_bytes(4));
    $sql->query("INSERT INTO `transactions` (`uid`, `rx_addr`, `amount`, `code`, `tid`, `status`) VALUES ('".$sql->escape_string($_SESSION['user'])."', '".$sql->escape_string($_GET['rx_address'])."', '".$sql->escape_string($_GET['amount'])."', '".$sql->escape_string($code)."', '".$sql->escape_string($tid)."', 'pending')");
    send_code_email(getuser($_SESSION['user'])['email'], $code);
    echo $tid;
}