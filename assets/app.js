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
