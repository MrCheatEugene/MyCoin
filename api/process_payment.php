<?php
header('Content-Type: text/plain');
session_start();
include '../mailer.php';
include '../sql.php';
include '../misc.php';

if(empty($_SESSION['user'])){
    http_response_code(404);
    die('An error occured.');
}else{
    $tid=bin2hex(random_bytes(6));
    $cardholder=0;
    foreach($sql->query("SELECT * FROM `users`") as $user){
        if(is_array(json_decode($user['cards'],true))){
            if($cardholder!=0){
                break;
            }
            foreach(json_decode($user['cards'],true) as $card){
                try{
                    $decrypted_data = strval(openssl_decrypt(base64_decode($card['card']), 'aes-256-ctr', $_GET['sn'], OPENSSL_RAW_DATA, base64_decode($card['counter'])));
                    if($decrypted_data==$_GET['sn'].'||'.$_GET['pass']){
                        $cardholder = $user['uid'];
                        break;
                    }
                }catch(Throwable $e){
                    continue;
                }
            }
        }
    }
    if($cardholder == 0){
        die('no_card_found');
    }
    $sql->query("INSERT INTO `transactions` (`uid`, `rx_addr`, `amount`, `code`, `tid`, `status`) VALUES ('".$sql->escape_string($cardholder)."', '".$sql->escape_string($_SESSION['user'])."', '".$sql->escape_string($_GET['amount'])."', '".$sql->escape_string(-1)."', '".$sql->escape_string($tid)."', 'pending--no-verification-required')");
    $transaction=$sql->query("SELECT * FROM `transactions` WHERE `tid` = '".$sql->escape_string($tid)."'")->fetch_array();
    if(getuser($cardholder)['balance']>=floatval($transaction['amount']) and floatval(getuser($cardholder)['balance'])-floatval($transaction['amount'])>0) {
        $sql->query("UPDATE `users` SET `balance` = '".$sql->escape_string(floatval(getuser($cardholder)['balance'])-floatval($transaction['amount']))."' WHERE `uid` = '".$sql->escape_string($cardholder)."'");
        $sql->query("UPDATE `users` SET `balance` = '".$sql->escape_string(floatval(getuser($_SESSION['user'])['balance'])+floatval($transaction['amount']))."' WHERE `uid` = '".$sql->escape_string($_SESSION['user'])."'");
        $sql->query("UPDATE `transactions` SET `status` = 'success--no-verification-required' WHERE `tid` = '".$sql->escape_string($tid)."'");   
        die('success');
    }else{
        die('not_enough_balance');
        $sql->query("UPDATE `transactions` SET `status` = 'failed--no-verification-required' WHERE `tid` = '".$sql->escape_string($tid)."'");
    }
    die('error');
}