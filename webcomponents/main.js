fetch('./message.php').then(res => res.text()).then(res =>{
    document.querySelector('#content').innerHTML = res;
})