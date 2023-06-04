<?php
    header('Content-Type: text/plain');
    $in=file_get_contents('php://input');
    include '../sql.php';
    include '../misc.php';
    session_start();
    if(!isset($_SESSION['user'])){
        die('false');
    }
    parse_str(base64_decode($in),$_POST);
    $cards = json_decode(getuser($_SESSION['user'])['cards'],true);
    if(!is_array($cards)){
        $cards=[];
        setcards($_SESSION['user'], json_encode($cards));
    }
    if(in_array($_POST['msg'] ,$cards)){
        die('true');
    }
    die('false');
?>