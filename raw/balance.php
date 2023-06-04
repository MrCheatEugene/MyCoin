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
    <h3 class="ms-3">Личный кабинет</h3>
    <div class="d-flex flex-row container mt-3" style="flex-wrap: wrap;">
        <div class="p-3 mb-3 text-bg-primary rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Баланс</h4>
            <p><?php echo $user['balance']; ?> Mycoin</p>
        </div>
        <div class="p-3 mb-3 text-bg-success rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Дата регистрации</h4>
            <p><?php echo date_create_from_format('Y-m-d H:i:s',$user['registered'])->format("d.m.Y H:i:s"); ?></p>
        </div>
        <div class="p-3 mb-3 text-bg-warning rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Физических карт подключено</h4>
            <p><?php echo $cards; ?></p>
        </div>
        <div class="p-3 mb-3 text-bg-secondary rounded-3 w-25 me-3" style="flex-basis: max-content;">
            <h4>Номер клиента</h4>
            <p><?php echo $_SESSION['user']; ?></p>
        </div>
    </div>
</div>