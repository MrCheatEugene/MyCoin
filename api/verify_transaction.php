<?php
header('Content-Type: text/plain');
session_start();
include '../mailer.php';
include '../sql.php';
include '../misc.php';

if(empty($_SESSION['user']) or empty($_GET['tid']) or empty($_GET['code'])){
    http_response_code(404);
    die('An error occured.');
}else{
    $tid=$_GET['tid'];
    $code=$_GET['code'];
    if($sql->query("SELECT * FROM `transactions` WHERE `tid` = '".$sql->escape_string($tid)."' AND `code` = '".$sql->escape_string($code)."' AND `status`='pending'")->num_rows==1){
        $transaction=$sql->query("SELECT * FROM `transactions` WHERE `tid` = '".$sql->escape_string($tid)."'")->fetch_array();
        if(getuser($_SESSION['user'])['balance']>=floatval($transaction['amount']) and floatval(getuser($_SESSION['user'])['balance'])-floatval($transaction['amount'])>0) {
            $sql->query("UPDATE `users` SET `balance` = '".$sql->escape_string(floatval(getuser($_SESSION['user'])['balance'])-floatval($transaction['amount']))."' WHERE `uid` = '".$sql->escape_string($_SESSION['user'])."'");
            $sql->query("UPDATE `users` SET `balance` = '".$sql->escape_string(floatval(getuser($transaction['rx_addr'])['balance'])+floatval($transaction['amount']))."' WHERE `uid` = '".$sql->escape_string($transaction['rx_addr'])."'");
            $sql->query("UPDATE `transactions` SET `status` = 'success' WHERE `tid` = '".$sql->escape_string($tid)."'");   
            die('success');
        }else{
            die('not_enough_balance');
            $sql->query("UPDATE `transactions` SET `status` = 'failed' WHERE `tid` = '".$sql->escape_string($tid)."'");
        }
    }else{
        die('invalid_code_or_transaction');
    }
    die('error');
}