<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
    include 'sql.php';
    $user = getuser($_SESSION['user']);
?>

<div class="w-100 d-flex container mt-3 flex-column">
    <h3 class="ms-3">Транзакции</h3>
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
        <div class="table-responsive">
            <table class="table">
                <thead class="text-white">
                    <tr>
                        <th scope="col">Отправитель</th>
                        <th scope="col">Получатель</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Ссылка</th>
                        <th scope="col">Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 

                        foreach($sql->query("SELECT * FROM `transactions` WHERE `uid` = '".$sql->escape_string($_SESSION['user'])."' OR `rx_addr` = '".$sql->escape_string($_SESSION['user'])."' ORDER BY `date` DESC") as $transaction){
                            switch($transaction['status']){
                                case 'success':
                                    $transaction['status']='Успех';
                                    break;
                                case 'failed':
                                    $transaction['status']='Ошибка';
                                    break;
                                case 'pending':
                                    $transaction['status']='В процессе';
                                    break;
                                case 'pending--no-verification-required':
                                    $transaction['status']='В процессе (ПЛАТЕЖ БЕЗ УСИЛЕННОЙ ВАЛИДАЦИИ)';
                                    break;
                                case 'failed--no-verification-required':
                                    $transaction['status']='Ошибка (ПЛАТЕЖ БЕЗ УСИЛЕННОЙ ВАЛИДАЦИИ)';
                                    break;
                                case 'success--no-verification-required':
                                    $transaction['status']='Успех (ПЛАТЕЖ БЕЗ УСИЛЕННОЙ ВАЛИДАЦИИ)';
                                    break;
                            }
                            
                            echo '<tr class="text-white">
                                <th scope="row">'.htmlspecialchars($transaction['uid']).'</th>
                                <td>'.htmlspecialchars($transaction['rx_addr']).'</td>
                                <td>'.htmlspecialchars($transaction['amount']).' MyCoin</td>
                                <td>'.htmlspecialchars($transaction['status']).'</td>
                                <td><a href="index.php?do=transaction&tid='.htmlspecialchars($transaction['tid']).'">Транзакция</a></td>
                                <td>'.htmlspecialchars(date_create_from_format('Y-m-d H:i:s',$transaction['date'])->format("d.m.Y H:i:s")).'</td>
                            </tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>