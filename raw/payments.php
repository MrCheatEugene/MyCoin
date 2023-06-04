<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
?>


<script src="assets/js/md5.js"></script>
<script src="assets/js/payments.js"></script>

<?php 
    if(isset($_GET['amount'])){
?>
<script>bill(<?php echo htmlspecialchars($_GET['amount']) ?>)</script>
<div class="w-100 d-flex justify-content-center flex-column container text-center mt-3">
    <h3>Приложите карту/тег</h3>
    <p>Для оплаты счета на <?php echo htmlspecialchars($_GET['amount']); ?> MyCoin</p>
    <button type="button" class="btn btn-danger" onclick="window.location.href='index.php?do=payments'">Отмена</button>
</div>
<?php
    }else{
?>
<div class="w-100 d-flex justify-content-center flex-column container text-center mt-3">
    <h3>Выставить счет</h3>
    <div class="mb-3">
        <label for="amount" class="form-label">Сумма</label>
        <input type="text" class="form-control" id="amount" placeholder="100">
    </div>
    <button type="button" class="btn btn-success" onclick="window.location.href='index.php?do=payments&amount='+encodeURIComponent(document.getElementById('amount').value)">Выставить</button>
</div>
<?php
    }
?>