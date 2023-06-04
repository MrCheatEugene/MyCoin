<?php 
    if(!isset($_SESSION['user'])){
        die('<script src="assets/js/login_redirect.js"></script>');
    }
?>


<script src="assets/js/md5.js"></script>
<script>
    if (!('NDEFReader' in window)) {
        document.getElementById('nfc_required').classList.add('d-none');
        alert("В вашем браузере/устройстве нет поддержки Web NFC. Сайт будет работать в ограниченном режиме.");
    }

    const ndef = new NDEFReader();
    const enc = new TextEncoder();
    const controller = new AbortController();
    const signal = controller.signal;

    const encodeKey = async (key)=> {
        let key1 = new Uint8Array(key.buffer);
        let key2 = new Uint8Array(new ArrayBuffer(32));
        key2.set(key1);
        const key_encoded = await crypto.subtle.importKey(
            "raw",
            key2,
            "AES-CTR",
            false,
            ["encrypt", "decrypt"]
        );
        return key_encoded;
    }

    const decryptMessage = async (key,counter,ciphertext)=>{
        let decrypted = await window.crypto.subtle.decrypt(
        {
            name: "AES-CTR",
            counter,
            length: 64
        },
        key,
        ciphertext
        );

        let dec = new TextDecoder();
        return dec.decode(decrypted);
    }

    const _arrayBufferToBase64 = ( buffer ) => {
        var binary = '';
        var bytes = new Uint8Array( buffer );
        var len = bytes.byteLength;
        for (var i = 0; i < len; i++) {
            binary += String.fromCharCode( bytes[ i ] );
        }
        return window.btoa( binary ); 
    }

    let currentcard=null;
    let working=false;

    const bill = async(amount)=>{
        if (!('NDEFReader' in window)) {
            alert("В вашем браузере/устройстве нет поддержки Web NFC. ");
        }
        if(ndef==undefined){
            const ndef = new NDEFReader();
        }
        if(working){
            return;
        }
        working=true;
        ndef.addEventListener("reading",  async (event) => {
            const serialNumber = event.serialNumber;
            const passphrase = prompt("Укажите кодовое слово.");
            const result = (await (await fetch('api/process_payment.php?pass='+encodeURIComponent(md5(passphrase))+'&sn='+encodeURIComponent(serialNumber)+'&amount='+encodeURIComponent(amount))).text());
            console.log(result);
            if(result=='success'){
                alert("Платеж успешен.");
            }else if(result == 'not_enough_balance'){
                alert("Недостаточно средств.");
            }else if(result == 'no_card_found'){
                alert("Карта не читается.");
            }else{
                alert("Неизвестная ошибка.");
            }
            controller.abort();
            window.location.href = 'index.php?do=pay_card';
            return;
        });
        ndef.onreadingerror = (event) => {
            alert("Ошибка чтения карты.");
            working=false;
            window.location.href = 'index.php?do=pay_card';
        };
        await ndef.scan({signal: signal});
    };
</script>

<?php 
    if(isset($_GET['amount'])){
?>
<script>bill(<?php echo htmlspecialchars($_GET['amount']) ?>)</script>
<div class="w-100 d-flex justify-content-center flex-column container text-center mt-3">
    <h3>Приложите карту/тег</h3>
    <p>Для оплаты счета на <?php echo htmlspecialchars($_GET['amount']); ?> MyCoin</p>
    <button type="button" class="btn btn-danger" onclick="window.location.href='index.php?do=pay_card'">Отмена</button>
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
    <button type="button" class="btn btn-success" onclick="window.location.href='index.php?do=pay_card&amount='+encodeURIComponent(document.getElementById('amount').value)">Выставить</button>
</div>
<?php
    }
?>