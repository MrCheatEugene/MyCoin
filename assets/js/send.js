let transaction_id = 0;

const send = async ()=>{
    document.querySelector('[onclick="send()"]').setAttribute('onclick', 'check_code()');
    document.getElementById('rx_address').disabled=true;
    document.getElementById('amount').disabled=true;
    document.getElementById('rx_code').classList.remove('d-none');
    try{
        alert("Код подтверждения был отправлен вам на E-mail. Введите его для подтверждения.");
        transaction_id = (await (await fetch('api/new_transaction.php?rx_address='+encodeURIComponent(document.getElementById('rx_address').value)+'&amount='+encodeURIComponent(document.getElementById('amount').value))).text());
    }catch{
        alert("Ошибка создания транзакции.");
    }
};

const check_code = async()=>{
    try{
        if(transaction_id==0){
            alert("Транзакция не создана.");
        }
        const result = (await (await fetch('api/verify_transaction.php?tid='+encodeURIComponent(transaction_id)+'&code='+encodeURIComponent(document.getElementById('email_code').value))).text());
        if(result == 'invalid_code_or_transaction'){
            alert("Неверный код, или транзакция.");
        }else if(result == 'not_enough_balance'){
            alert("Недостаточно средств.");
        }else if(result == 'error'){
            alert("Неизвестная ошибка.");
        }else{
            alert("Перевод был успешен.");
        }
        window.location.href='index.php?do=transaction&tid='+encodeURIComponent(transaction_id);
    }catch{
        alert("Ошибка проверки транзакции.");
        window.location.href='index.php?do=transaction&tid='+encodeURIComponent(transaction_id);
    }
};