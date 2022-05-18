/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// app.js

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
$(document).ready(function () {
    $('[data-toggle="popover"]').popover;
})


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
//import $ from 'jquery';
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import { data } from 'jquery';

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

//bare de recherche


const search = document.getElementById('search-product');
const submit = document.getElementById('submit');

submit.addEventListener("click", (e) => {

    if (search.value == "" || search.value.length <= 4) {

        search.classList.add("is-invalid");
        e.preventDefault();
    }

})
//gestion de panier
let productInCart = JSON.parse(localStorage.getItem("product") || "[]");
const panier = document.querySelectorAll(".product-cart");
const sumPrices = document.querySelector('.sumPrice');
const sumCounts = document.querySelector('.numb-cart');
const deleteInCart = document.querySelectorAll(".bi-caret-left-fill");
const deleteFromCart = document.querySelectorAll(".delete");

if (deleteFromCart) {
    deleteFromCart.forEach((e) => {
        e.addEventListener('click', () => {

            for (let i = 0; i < productInCart.length; i++) {
                if (productInCart[i].id == parseInt(e.getAttribute("data-product-id"))) {
                    productInCart[i].count = 0;
                    productInCart[i].price = productInCart[i].basePrice * productInCart[i].count;
                }

            }
            localStorage.setItem("product", JSON.stringify(productInCart));
            updateShopCart()

        })
    })
}

if (deleteInCart) {
    deleteInCart.forEach((e) => {
        e.addEventListener('click', () => {

            for (let i = 0; i < productInCart.length; i++) {
                if (productInCart[i].id == parseInt(e.getAttribute("data-product-id"))) {
                    productInCart[i].count -= 1;
                    productInCart[i].price = productInCart[i].basePrice * productInCart[i].count;
                }

            }
            localStorage.setItem("product", JSON.stringify(productInCart));
            updateShopCart()

        })
    })
}




function countSumPrice() {
    let sumPrice = 0;
    productInCart.forEach(product => {
        sumPrice += product.price;
    });

    return sumPrice;
}
function storageJson() {

}
function countSumCount() {
    let sumCount = 0;
    productInCart.forEach(product => {
        sumCount += product.count;
    });

    return sumCount;
}

function updateToCart(products) {
    for (let i = 0; i < productInCart.length; i++) {
        if (productInCart[i].id == products.id) {
            productInCart[i].count += 1;
            productInCart[i].price = productInCart[i].basePrice * productInCart[i].count;
            return;
        }

    }
    productInCart.push(products);
}

function updateShopCart() {


    if (productInCart.length > 0) {
        sumPrices.innerHTML = ""
        sumCounts.innerHTML = ""
        sumPrices.insertAdjacentText("afterbegin", countSumPrice());
        sumCounts.insertAdjacentText("afterbegin", countSumCount());

    }
}
updateShopCart();

panier.forEach(cart => {
    cart.addEventListener("click", (e) => {

        if (e.target.classList.contains("addToCart")) {
            const productId = cart.querySelector(".addToCart").getAttribute('data-product-id');
            const productName = cart.querySelector(".product-title").innerHTML;
            const productPrice = cart.querySelector(".product-price").getAttribute("value");
            const productImg = cart.querySelector("img").src;

            let productToCart = {
                name: productName,
                image: productImg,
                id: +productId,
                count: 1,
                price: +productPrice,
                basePrice: +productPrice

            }
            updateToCart(productToCart);

            localStorage.setItem("product", JSON.stringify(productInCart));
            updateShopCart();
        }
        console.log(productInCart, cart);
    })
})
countSumCount();
countSumPrice();