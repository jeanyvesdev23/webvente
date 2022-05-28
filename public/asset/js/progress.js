//carousolle home
var progress = document.getElementById('progress').getAttribute("value");
progress = parseInt(progress);
var button = document.getElementById('button').style.display = 'none'

if (progress == 1) {
    let span = document.querySelector('#span .bi-check-circle-fill').style.color = "blue";
    let list = document.querySelectorAll('#liste');
    for (let i = 0; i < list.length; i++) {
        list[i].innerText = "Commande effectué";

    }


} if (progress == 2) {
    let span = document.querySelector('#span .bi-check-circle-fill').style.color = "blue";
    let w25 = document.querySelector('#w25 .line').style.background = "blue";
    let span1 = document.querySelector('#span1 .bi-check-circle-fill').style.color = "blue";
    let list = document.querySelectorAll('#liste');
    for (let i = 0; i < list.length; i++) {
        list[i].innerText = "Commande expédié";

    }
    var button = document.getElementById('button');
    button.setAttribute('style', 'display:block')


} if (progress == 4) {
    let span = document.querySelector('#span .bi-check-circle-fill').style.color = "blue";
    let w25 = document.querySelector('#w25 .line').style.background = "blue";
    let span1 = document.querySelector('#span1 .bi-check-circle-fill').style.color = "blue";
    let w251 = document.querySelector('#w251 .line').style.background = "blue";
    let span2 = document.querySelector('#span2 .bi-check-circle-fill').style.color = "blue";
    let list = document.querySelectorAll('#liste');
    for (let i = 0; i < list.length; i++) {
        list[i].innerText = "Commande en livraison";
    }

    let buton = document.getElementById('button');
    let bu = buton.setAttribute('style', "display:block")
    buton.innerText = "ANULLER IMPOSSIBLE (COMMANDE EN LIVRAISON)"

} if (progress == 3) {
    let span = document.querySelector('#span .bi-check-circle-fill').style.color = "blue";
    let w25 = document.querySelector('#w25 .line').style.background = "blue";
    let span1 = document.querySelector('#span1 .bi-check-circle-fill').style.color = "blue";
    let w251 = document.querySelector('#w251 .line').style.background = "blue";
    let span2 = document.querySelector('#span2 .bi-check-circle-fill').style.color = "blue";
    let w252 = document.querySelector('#w252 .line').style.background = "blue";
    let span3 = document.querySelector('#span3 .bi-check-circle-fill').style.color = "blue";
    let list = document.querySelectorAll('#liste');
    for (let i = 0; i < list.length; i++) {
        list[i].innerText = "Commande livré";

    }
    let button = document.getElementById('button');
    let bu = button.setAttribute('style', "display:block")
    button.innerText = "DEMANDE DE RETOUR EN 7J"

}

console.log(w25)