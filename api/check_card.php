<?php
    header('Content-Type: text/plain');
    $in=file_get_contents('php://input');
    parse_str(base64_decode($in),$_POST);
    $decrypted_data = strval(openssl_decrypt(base64_decode($_POST['msg']), 'aes-256-ctr', $_POST['sn'], OPENSSL_RAW_DATA, base64_decode($_POST['counter'])));
    echo $decrypted_data;
?>