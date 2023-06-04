<?php 
    session_start();
    $baseurl='https://vds.mrcheat.org/nfc';
    include 'misc.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyCoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="assets/css/main.css" rel="stylesheet">
  </head>
  <body class="bg-dark text-white">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">MyCoin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo $baseurl; ?>/index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo $baseurl; ?>/index.php?do=transfer">Переводы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo $baseurl; ?>/index.php?do=cards">Физические карты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo $baseurl; ?>/index.php?do=transactions">Транзакции</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo $baseurl; ?>/index.php?do=payments">Касса</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $baseurl; ?>/index.php?do=balance">Личный кабинет</a>
                </li>
                <?php 
                    if (empty($_SESSION['user'])){
                        echo '<li class="nav-item"><a class="nav-link" href="'.$baseurl.'/index.php?do=login">Войти</a></li>';
                    }else{
                        echo '<li class="nav-item"><a class="nav-link" href="'.$baseurl.'/index.php?do=logout">Выйти</a></li>';
                    }
                ?>
                
            </ul>
            </div>
        </div>
    </nav>
    <?php
        if(isset($_GET['do'])){
            switch($_GET['do']){
                case 'transfer':
                    include 'raw/transfer.php';
                    break;
                case 'cards':
                    include 'raw/cards.php';
                    break;
                case 'payments':
                    include 'raw/payments.php';
                    break;
                case 'transaction':
                    include 'raw/transaction.php';
                    break;
                case 'transactions':
                    include 'raw/transactions.php';
                    break;
                case 'connect_card':
                    include 'raw/connect_card.php';
                    break;
                case 'login':
                    include 'raw/login.php';
                    break;
                case 'balance':
                    include 'raw/balance.php';
                    break;
                case 'logout':
                    session_unset();
                    redirect($baseurl."/index.php");
                    break;
                default:
                    break;
            }
        }else{
            include 'raw/index.php';
        }
    ?>
  </body>
</html>