if (!('NDEFReader' in window)) {
    document.getElementById('nfc_required').classList.add('d-none');
    alert("В вашем браузере/устройстве нет поддержки Web NFC. Сайт будет работать в ограниченном режиме.");
}

const ndef = new NDEFReader();
const enc = new TextEncoder();

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

const rmCard = async() =>{
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
    if(currentcard!=null){
        const passphrase = prompt("Вы ДЕЙСТВИТЕЛЬНО хотите удалить карту? Если вы не уверены, пожалуйста, нажмите \"Отмена\". Если вы уверены, то введите кодовую фразу карты.") 
        try{
            const key = (await encodeKey(enc.encode(currentcard['sn'])));
            const card = currentcard['card'];
            const msg = await decryptMessage(key, Uint8Array.from(atob(card['counter']), c => c.charCodeAt(0)),  Uint8Array.from(atob(card['card']), c => c.charCodeAt(0)));
            if(msg == currentcard['sn']+'||'+md5(passphrase)){
                if((await ((await fetch('api/card_delete.php', {method: 'post', body: window.btoa('card='+encodeURIComponent(card['card'])+'&counter='+encodeURIComponent(card['counter']))})).text())) == 'true'){
                    alert("Карта успешно удалена.");
                    document.getElementById('nfc_card').classList.add('d-none');
                    working=false;
                }else{
                    alert("Не удалось удалить карту - API вернуло критическую ошибку");
                    working=false;
                }
            }
        }catch(e){
            console.log(e);
            working=false;
            alert("Не удалось удалить карту - Критическая клиент-ошибка");
        }
    }
}

const readCard = async()=>{
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
    alert("Подготовьте карту, нажмите \"OK\", и приложите ее к вашему NFC-модулю.")
    ndef.addEventListener("reading",  async (event) => {
        const serialNumber = event.serialNumber;
        document.getElementById('carduid').innerText='Карта '+serialNumber;
        const passphrase = prompt("Укажите кодовую фразу, которую вы вводили при привязке карты.");
        const key = (await encodeKey(enc.encode(serialNumber)));
        const cards = (await (await fetch('api/cards_user.php')).json());
        for (card in cards){
            try{
                card=cards[card];
                currentcard={'card': card, 'sn': serialNumber};
                const msg = await decryptMessage(key, Uint8Array.from(atob(card['counter']), c => c.charCodeAt(0)),  Uint8Array.from(atob(card['card']), c => c.charCodeAt(0)));
                if(msg == event.serialNumber+'||'+md5(passphrase)){
                    alert("Карта найдена!");
                    document.getElementById('nfc_card').classList.remove('d-none');
                    working=false;
                    return true;
                } 
            }catch(e){
                console.log(e);
            }
        }
        alert("Карта не была найдена.");
        working=false;
        return false;
    });
    ndef.onreadingerror = (event) => {
        alert("Ошибка чтения карты.");
        working=false;
    };
    await ndef.scan();
};