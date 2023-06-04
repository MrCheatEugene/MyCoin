<?php 
include 'sql.php';
$transaction = $sql->query("SELECT * FROM `transactions` WHERE `tid` = '".$sql->escape_string($_GET['tid'])."'")->fetch_array();
$tdata = '<ul>
    <li>Отправитель: '.htmlspecialchars($transaction['uid']).'</li>
    <li>Получатель: '.htmlspecialchars($transaction['rx_addr']).'</li>
    <li>Сумма: '.htmlspecialchars($transaction['amount']).' MyCoin</li>
    <li>Transaction ID: '.htmlspecialchars($transaction['tid']).'</li>
    <li>Статус: '.htmlspecialchars($transaction['status']).'</li>
    <li>Дата: '.htmlspecialchars(date_create_from_format('Y-m-d H:i:s',$transaction['date'])->format("d.m.Y H:i:s")).'</li>
</ul>';
?>
<div class="d-flex w-100 mt-3 flex-column">
    <?php 
        $transaction['status_2']=str_replace('--no-verification-required','', $transaction['status']);
        if($transaction['status_2'] == 'pending'){
            //<div class="border border-success w-75" style="height: 75vh;"></div>
            //<div class="border border-danger w-75" style="height: 75vh;"></div>
            echo '<div class="border border-secondary w-75 ms-auto me-auto" style="height: 75vh;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 15%; height: 15%;" fill="currentColor" class="bi bi-question-circle-fill ms-auto me-auto d-flex mt-3" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                </svg>
                <div class="mt-3 container justify-content-center d-flex flex-column ms-auto">
                    <div class="ms-auto me-auto">
                        <h1>Транзакция в процессе</h1>
                        <p>Транзакция ещё обрабатывается.</p>
                        '.$tdata.'
                    </div>
                </div>
            </div>';
        }elseif($transaction['status_2'] == 'success'){
            echo '<div class="border border-success w-75 ms-auto me-auto" style="height: 75vh;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 15%; height: 15%;" fill="currentColor" class="bi bi-question-circle-fill ms-auto me-auto d-flex mt-3" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <div class="mt-3 container justify-content-center d-flex flex-column">
                    <div class="ms-auto me-auto">
                        <h1 class="text-success">Транзакция успешна</h1>
                        <p>Деньги успешно переведены.</p>
                        '.$tdata.'
                    </div>
                </div>
            </div>';
        }elseif($transaction['status_2'] == 'failed'){
            echo '<div class="border border-danger w-75 ms-auto me-auto" style="height: 75vh;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 15%; height: 15%;" fill="currentColor" class="bi bi-question-circle-fill ms-auto me-auto d-flex mt-3" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                </svg>
                <div class="mt-3 container justify-content-center d-flex flex-column">
                    <div class="ms-auto me-auto">
                        <h1 class="text-danger">Транзакция неуспешна</h1>
                        <p>Деньги не были переведены.</p>
                        '.$tdata.'
                    </div>
                </div>
            </div>';
        }
    ?>
</div>