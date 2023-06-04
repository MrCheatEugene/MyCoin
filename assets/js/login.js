const switchforms = ()=>{
    const regform = document.getElementById('reg_form');
    const logform = document.getElementById('login_form');
    if(regform.classList.contains('d-none') && logform.classList.contains('d-none')==false){
        regform.classList.remove('d-none')
        logform.classList.add('d-none');
    }else{
        regform.classList.add('d-none')
        logform.classList.remove('d-none');
    }
};