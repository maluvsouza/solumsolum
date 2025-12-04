document.addEventListener("click", function(e) {

    if (e.target.classList.contains("btn-remover-fav")) {
        const id = e.target.getAttribute("data-remove-id");

        fetch("toggle_favorite.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "product_id=" + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "removed") {

                let card = document.querySelector(`.produto-favorito[data-id="${id}"]`);

                card.classList.add("removendo");

                setTimeout(() => {
                    card.nextElementSibling?.remove(); // remove divisor
                    card.remove();

                    atualizarOffcanvasFavoritos();

                    if (data.count === 0) {
                        document.querySelector(".offcanvas-favoritos").innerHTML =
                            `<div class="favoritos-vazio">Nenhum favorito no momento.</div>`;
                    }
                }, 300);
            }
        });
    }

});
