<?php 
    include 'sql.php';
    if(isset($_SESSION['user'])){
        die('<script src="assets/js/index_redirect.js"></script>');
    }
    if(isset($_POST['do'])){
        switch($_POST['do']){
            case 'register':
                $uid=random_int(-999999999,999999999);
                if($_POST['password']!=$_POST['password_confirm']){
                    alert('Пароль не соответствует подтверждению.');
                }elseif(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)==false){
                    alert('E-mail не соответствует формату.');
                }elseif($sql->query("SELECT * FROM `users` WHERE `email` = '".$_POST['email']."' LIMIT 1")->num_rows==0 and $sql->query("INSERT INTO `users` (`uid`, `email`, `password`) VALUES ('".$sql->escape_string($uid)."','".$sql->escape_string($_POST['email'])."','".$sql->escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT))."')")){
                    $_SESSION['user'] = $uid;
                    $_SESSION['email'] = $email;
                    redirect('index.php?do=balance');
                }else{
                    alert("Ошибка регистрации.");
                }
                break;
            case 'auth':
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)==false){
                    alert('E-mail не соответствует формату.');
                }elseif($sql->query("SELECT * FROM `users` WHERE `email` = '".$sql->escape_string($_POST['email'])."' LIMIT 1")->num_rows==0){
                    alert("Аккаунт не найден.");
                }else{
                    $acc = $sql->query("SELECT * FROM `users` WHERE `email` = '".$sql->escape_string($_POST['email'])."' LIMIT 1")->fetch_array();
                    if(password_verify($_POST['password'], $acc['password'])){
                        if(password_needs_rehash($acc['password'], PASSWORD_DEFAULT)){
                            $sql->query("UPDATE `users` SET `password` = '".$sql->escape_string($_POST['password'])."' WHERE `email` = '".$sql->escape_string($_POST['email'])."'");
                        }
                        $_SESSION['user'] = $acc['uid'];
                        $_SESSION['email'] = $acc['email'];
                        redirect('index.php?do=balance');
                    }else{
                        alert("Неверный логин или пароль.");
                    }
                }
            default:
                break;
        }
    }
?>

<div class="w-50 d-flex flex-column container mt-3">
    <form id="login_form" method="POST" enctype="application/x-www-form-urlencoded">
        <h2>Авторизация</h2>
        <input type="hidden" name="do" value="auth">
        <div class="mb-3">
            <label class="form-label">Почта</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="d-flex flex-row gap-3 mt-3 flex-wrap">
            <button type="submit" class="btn btn-outline-primary">Войти</button>
            <a onclick="switchforms()" class="btn btn-outline-warning">Нет аккаунта?</a>
        </div>
    </form>

    <form id="reg_form" method="POST" enctype="application/x-www-form-urlencoded" class="d-none">
        <h2>Регистрация</h2>
        <input type="hidden" name="do" value="register">
        <div class="mb-3">
            <label class="form-label">Почта</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="">
            <label class="form-label">Подтвердите пароль</label>
            <input type="password" class="form-control" name="password_confirm">
        </div>
        <div class="d-flex flex-row gap-3 mt-3 flex-wrap">
            <button type="submit" class="btn btn-outline-primary">Регистрация</button>
            <a onclick="switchforms()" class="btn btn-outline-warning">Есть аккаунт?</a>
        </div>
    </form>
</div>

<script src="assets/js/login.js"></script>