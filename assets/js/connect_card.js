if (!('NDEFReader' in window)) {
    document.querySelector('[onclick="work();"]').disabled=true;
    document.querySelector('[onclick="work();"]').innerText="В вашем браузере нет поддержки Web NFC.";
}
let writing=false;

const ndef = new NDEFReader();
const enc = new TextEncoder();

const encryptMessage = async (key, encoded)=> {
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
    const counter = new Uint8Array(16);
    const counter2 = window.crypto.getRandomValues(counter);
    return ({'crypted': await window.crypto.subtle.encrypt(
        {
        name: "AES-CTR",
        counter,
        length: 64,
        },
        key_encoded,
        encoded
    ), 'counter': counter});
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

const work = async()=>{
    document.querySelector('[onclick="work();"]').disabled=true;
    document.querySelector('[onclick="work();"]').innerText="Приложите карту/тег";
    if(ndef==undefined){
        const ndef = new NDEFReader();
    }
    ndef.addEventListener("reading",  async ({msg, serialNumber}) => {
        const some_data = document.getElementById('data').value;
        if(writing){
            return;
        }else{
            let tries=0;
            while(tries<3){
                try{
                    writing=true;
                    await ndef.write({
                        records: [{ recordType: "url", data: "https://mycoin.mrcheat.org" }],
                    });
                    const test_data = serialNumber+'||'+md5(some_data);
                    const encrypted = await encryptMessage(enc.encode(serialNumber),enc.encode(test_data));
                    const result = (await (await fetch('api/check_card.php', {method: 'post', body: window.btoa('sn='+encodeURIComponent(serialNumber)+'&'+'msg='+encodeURIComponent(_arrayBufferToBase64(encrypted['crypted']))+'&counter='+encodeURIComponent(_arrayBufferToBase64(encrypted['counter'].buffer)))})).text());
                    if(result == test_data && (await (await fetch('api/card_update.php', {method: 'post', body: window.btoa('msg='+encodeURIComponent(_arrayBufferToBase64(encrypted['crypted']))+'&counter='+encodeURIComponent(_arrayBufferToBase64(encrypted['counter'].buffer)))})).text()) == 'true'){
                        alert("Готово! Карта подключена.");
                        writing=false; break; return;
                    }else{
                        alert('Ошибка при проверке, или обновлении зашифрованной информации.');
                        writing=false; break; return;
                    }
                }catch (e){
                    if(tries==3){
                        console.log(e);
                        alert("Ошибка записи служебной информации. Возможно, карта не записываема?");
                        writing=false; break; return;
                    }else{
                        tries+=1;
                    }
                }
            }
        }
    });
    ndef.onreadingerror = (event) => {
        alert("Ошибка чтения карты..");
    };
    await ndef.scan();
};