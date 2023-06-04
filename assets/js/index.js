const login = async ()=>{
    eval(await(await fetch('assets/js/login_redirect.js')).text()); // yep, this is a shitcode 😳
    // and yep, it's really dangerous, but only in case somebody hacks into my SSL
};

window.ityped.init(document.querySelector('[id=mycoin_slogan]'),{
    strings: ['Вы сами создаёте кошелек.','Вы сами выпускаете карту.', 'Вы сами переводите. Без коммиссии.','MyCoin - это вы.'],
    loop: true
});