<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
    include 'sql.php';
    $user = getuser($_SESSION['user']);
?>

<div class="w-100 d-flex container mt-3 flex-column">
    <h3 class="ms-3">Переводы</h3>
    <div class="d-flex flex-row container mt-3" style="flex-wrap: wrap;">
        <div class="p-3 mb-3 text-bg-primary rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Баланс</h4>
            <p><?php echo $user['balance']; ?> Mycoin</p>
        </div>
        <div class="p-3 mb-3 text-bg-secondary rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Номер клиента</h4>
            <p><?php echo $_SESSION['user']; ?></p>
        </div>
    </div>
    <div class="d-flex flex-column container mt-3 mw-75">
        <div class="form">
            <div class="mb-3">
                <label for="rx_address" class="form-label">Адрес получателя</label>
                <input type="text" class="form-control" id="rx_address" placeholder="0000000001">
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Сумма</label>
                <input type="text" class="form-control" id="amount" placeholder="100">
            </div>
            <div class="mb-3 d-none" id="rx_code">
                <label for="email_code" class="form-label">Код подтверждения</label>
                <input type="text" class="form-control" id="email_code" placeholder="1234">
            </div>
            <button onclick="send()" class="btn btn-primary">Отправить</button>
        </div>
    </div>
</div>
<script src="assets/js/send.js"></script>