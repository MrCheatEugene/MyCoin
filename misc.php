<?php 

function alert($msg){
    echo '<script async>alert("'.$msg.'")</script>';
}

function redirect($url){
    echo '<script>window.location.href="'.$url.'"</script>';
}

function getuser($id){
    global $sql;
    return $sql->query("SELECT * FROM `users` WHERE `uid` = '".$sql->escape_string($id)."' LIMIT 1")->fetch_array();
}

function setcards($id, $cards){
    global $sql;
    return $sql->query("UPDATE `users` SET `cards` = '".$sql->escape_string($cards)."' WHERE `uid` = '".$sql->escape_string($id)."'");
}