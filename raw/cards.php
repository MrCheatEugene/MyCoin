<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
    include 'sql.php';
    $user = getuser($_SESSION['user']);
    try{
        $cards = count(json_decode($user['cards']));
    }catch(TypeError $e){
        $cards = 0;
    }
?>

<div class="w-100 d-flex flex-row container mt-3 flex-column">
    <h3 class="ms-3">Ваши карты</h3>
    <div class="d-flex flex-row container mt-3" style="flex-wrap: wrap;">
        <div class="p-3 mb-3 text-bg-primary rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Физических карт подключено</h4>
            <p><?php echo $cards; ?></p>
        </div>
        <div class="d-flex" style="flex-wrap: wrap;" id="nfc_required">
            <div class="p-3 mb-3 text-bg-success rounded-3 w-25 me-3" style="flex-basis: max-content;">
                <h4>Хотите добавить карту?</h4>
                <p>Нажмите <a style="color:white;" href="?do=connect_card">сюда</a></p>
            </div>
            <div class="p-3 mb-3 text-bg-secondary rounded-3 w-25 me-3" style="flex-basis: max-content;">
                <h4>Хотите узнать информацию о карте, или удалить ее?</h4>
                <p>Нажмите <a style="color:white;" href="javascript:void(0)" onclick="readCard()">сюда</a>, и приложите ее к модулю NFC на вашем устройстве</p>
            </div>
            <div class="p-3 mb-3 text-bg-danger rounded-3 w-25 me-3 d-none" style="flex-basis: max-content;" id="nfc_card">
                <h4 id="carduid">** Тут появится UID карты **</h4>
                <p id="cardactions"><a style="color:white;" href="javascript:void(0)" onclick="rmCard()">Удалить карту</a></p>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/md5.js"></script>
<script src="assets/js/card.js"></script>