const wish = document.querySelector('.favorite');
const icons = document.querySelector(".icons");
const value = document.querySelector('.value');
if (wish) {

    wish.addEventListener("click", (e) => {
        e.preventDefault();
        let url = wish.getAttribute('href');
        $.ajax({
            type: "POST",
            url: url,
            success: function (response) {
                if (wish.classList.contains('text-danger')) {
                    wish.classList.replace("text-danger", "text-dark");

                } else {
                    wish.classList.replace("text-dark", "text-danger");
                }
                if (icons.classList.contains('bi-hand-thumbs-up-fill')) {
                    icons.classList.replace("bi-hand-thumbs-up-fill", "bi-hand-thumbs-up");

                } else {
                    icons.classList.replace("bi-hand-thumbs-up", "bi-hand-thumbs-up-fill");
                }
                value.innerHTML = response.data;
            }


        })
    })
}