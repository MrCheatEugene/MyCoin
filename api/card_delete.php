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
    }
    if(!in_array(['card'=>$_POST['card'], 'counter'=> $_POST['counter']] ,$cards)){
        die('false');
    }
    $c2=[];
    foreach($cards as $card){
        if($card ==  ['card'=>$_POST['card'], 'counter'=> $_POST['counter']]){
            continue;
        }
        array_push($c2, $card);
    }
    if(setcards($_SESSION['user'], json_encode($c2))!==false){
        die('true');
    }
    die('false');
?>