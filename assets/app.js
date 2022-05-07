/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// app.js

//const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
import $ from 'jquery';
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

//dashboard menu

let bcd = document.querySelectorAll(".bcd");
for (let i = 0; i < bcd.length; i++) {
    bcd[i].addEventListener("click", (e) => {
        let bcdParent = e.target.parentElement.parentElement;
        console.log(bcdParent);
        bcdParent.classList.toggle("showMenu");

    });

}
//liste de produit next et previous
const productContainer = [...document.querySelectorAll(".product-container")];
const prev = [...document.querySelectorAll(".prev")];
const next = [...document.querySelectorAll(".next")];

productContainer.forEach((item, i) => {
    let containtDim = item.getBoundingClientRect();
    let containtWidth = containtDim.width;

    next[i].addEventListener('click', () => {
        item.scrollLeft += containtWidth;
    })
    prev[i].addEventListener('click', () => {
        item.scrollLeft -= containtWidth;
    })
})
//liste produit news , best et futur
const all = document.getElementById("All");
const list_produit = document.getElementById("list-produit");
const news = document.getElementById("news");
const list_news = document.getElementById("list-news");
const best = document.getElementById("best");
const list_best = document.getElementById("list-best");
const offre = document.getElementById("offre");
const list_offre = document.getElementById("list-offre");


news.addEventListener("click", (e) => {
    list_produit.classList.add('none')
    list_news.classList.replace("none", "block");
    list_best.classList.replace("block", "none");
    list_offre.classList.replace("block", "none");
    news.classList.add('active')
    best.classList.remove('active')
    all.classList.remove('active')
    offre.classList.remove('active')


})
best.addEventListener("click", () => {
    list_produit.classList.add("none");
    list_best.classList.replace("none", "block");
    list_news.classList.replace("block", "none");
    list_offre.classList.replace("block", "none");
    best.classList.add('active')
    news.classList.remove('active')
    offre.classList.remove('active')
    all.classList.remove('active')
})
all.addEventListener("click", () => {
    list_produit.classList.remove("none");
    list_news.classList.replace("block", "none");
    list_best.classList.replace("block", "none");
    list_offre.classList.replace("block", "none");
    all.classList.add('active')
    best.classList.remove('active')
    news.classList.remove('active')
    offre.classList.remove('active')
})
offre.addEventListener("click", () => {
    list_produit.classList.add("none");
    list_news.classList.replace("block", "none");
    list_best.classList.replace("block", "none");
    list_offre.classList.replace("none", "block");
    offre.classList.add('active')
    best.classList.remove('active')
    news.classList.remove('active')
    all.classList.remove('active')
})