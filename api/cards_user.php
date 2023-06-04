<?php
    header('Content-Type: application/json');
    $in=file_get_contents('php://input');
    include '../sql.php';
    include '../misc.php';
    session_start();
    if(!isset($_SESSION['user'])){
        die('[]');
    }
    parse_str(base64_decode($in),$_POST);
    $cards = json_decode(getuser($_SESSION['user'])['cards'],true);
    if(!is_array($cards)){
        $cards=[];
    }
    die(json_encode($cards));