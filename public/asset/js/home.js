//carousolle home
const img_slide = document.querySelectorAll('.img-slide');

img_slide[0].classList.add("active");

img_slide.forEach(img => {
    img.addEventListener("click", () => {
        document.querySelector('.img-slides .active').classList.remove('active');
        img.classList.add('active');
        let src = img.getAttribute("data-src");
        document.querySelector('#img-sli').src = src;
    })
})