<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
?>

<div class="w-100 d-flex justify-content-center flex-column container text-center mt-3">
    <h3>Подключить карту</h3>
    <p>Введите кодовое слово (запомните его, без него мы не сможем найти карту!), и нажмите "Подключить карту"</p>
    <input class="form-control form-control mb-3" type="text" placeholder="Кодовое слово" id="data">
    <button type="button" class="btn btn-primary" onclick="work();">Подключить карту</button>
</div>
<script src="assets/js/md5.js"></script>
<script src="assets/js/connect_card.js"></script>