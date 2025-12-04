function trocarImagem(imagemClicada) {
    const imagemPrincipal = document.getElementById('imagemPrincipal');

  
    const tempSrc = imagemPrincipal.src;
    imagemPrincipal.src = imagemClicada.src;
    imagemClicada.src = tempSrc;
  }
  

document.addEventListener('DOMContentLoaded', function () {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');

    // update cart badge on load
    const cartCountEl = document.getElementById('contadorCarrinho');
    try {
        const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
        if (cartCountEl) cartCountEl.textContent = cart.length;
    } catch (e) {
        console.error('Erro ao ler carrinho do localStorage', e);
    }

   favoriteButtons.forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        const productId = this.getAttribute('data-product-id');

        fetch('toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_id=' + encodeURIComponent(productId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro do servidor:', data.error);
                return;
            }

            if (data.status === 'added') {
                this.classList.add('active');
                showFavToast("Adicionado aos favoritos!");
            } else if (data.status === 'removed') {
                this.classList.remove('active');
                showFavToast("Removido dos favoritos!");
            }

            // Atualiza contador, se existir
            const favCountEl = document.getElementById('contadorFavoritos');
            if (favCountEl && typeof data.count !== 'undefined') {
                favCountEl.textContent = data.count;
            }

            atualizarOffcanvasFavoritos();
        })
        .catch(error => console.error('Erro:', error));
    });
});


    function atualizarOffcanvasFavoritos() {
        fetch('getFavorites.php')
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('favoritosContainer') || document.querySelector('#offcanvasScrolling .offcanvas-body');
                if (container) container.innerHTML = html;
            });
    }
    // load favorites once on page load so offcanvas has content
    atualizarOffcanvasFavoritos();
});



function buscarProdutos(event) {
    event.preventDefault();

    const termo = document.getElementById('inputPesquisa').value;

    fetch(`buscar-produtos.php?q=${encodeURIComponent(termo)}`)
        .then(response => response.json())
        .then(produtos => {
          
            const resultadosDiv = document.getElementById('resultados');
            resultadosDiv.innerHTML = ''; 

            if (produtos.length === 0) {
                resultadosDiv.innerHTML = '<p>Nenhum produto encontrado.</p>';
            } else {
                produtos.forEach(prod => {
                    const item = document.createElement('div');
                    item.classList.add('produto-item');
                    item.innerHTML = `
                        <img src="${prod.proFoto}" alt="${prod.proNome}" width="100">
                        <h3>${prod.proNome}</h3>
                        <p>${prod.proDescricao}</p>
                        <p>Preço: R$ ${parseFloat(prod.proPreco).toFixed(2)}</p>
                    `;
                    resultadosDiv.appendChild(item);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao buscar produtos:', error);
        });
}
