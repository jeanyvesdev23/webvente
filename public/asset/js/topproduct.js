//liste produit news , best et futur
const all = document.getElementById("All");
const list_produit = document.getElementById("list-produit");
const news = document.getElementById("news");
const list_news = document.getElementById("list-news");
const best = document.getElementById("best");
const list_best = document.getElementById("list-best");
const offre = document.getElementById("offre");
const list_offre = document.getElementById("list-offre");
const cate = document.getElementById('search_Categorie');
const min = document.getElementById('search_minPrix');
const max = document.getElementById('search_maxPrix');
const cherch = document.getElementById('cherch');


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

cherch.addEventListener('click', (e) => {
    if (cate.value == "" && min.value == "" && max.value == "") {
        cate.classList.add('is-invalid');
        min.classList.add('is-invalid');
        max.classList.add('is-invalid');
        e.preventDefault();
    }
})