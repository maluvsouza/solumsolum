let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
let favoritos = JSON.parse(localStorage.getItem('favoritos')) || [];

document.addEventListener('DOMContentLoaded', function() {
    atualizarContadores();
    inicializarEventos();
});

function atualizarContadores() {
    const contadorCarrinho = document.getElementById('contadorCarrinho');
    const contadorFavoritos = document.getElementById('contadorFavoritos');
    
    if (contadorCarrinho) {
        contadorCarrinho.textContent = carrinho.length;
    }
    
    if (contadorFavoritos) {
        contadorFavoritos.textContent = favoritos.length;
    }
}

// Call this when cart/favorites change
function refreshBadges() {
    try {
        const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
        const favs = JSON.parse(localStorage.getItem('favoritos')) || [];
        const contadorCarrinho = document.getElementById('contadorCarrinho');
        const contadorFavoritos = document.getElementById('contadorFavoritos');
        if (contadorCarrinho) contadorCarrinho.textContent = cart.length;
        if (contadorFavoritos) contadorFavoritos.textContent = favs.length;
    } catch (e) {
        console.error('Erro ao atualizar badges', e);
    }
}

// Expose globally so other scripts can call it
window.refreshBadges = refreshBadges;


let carrinhoAtual = JSON.parse(localStorage.getItem('carrinho')) || [];
let cupomAplicado = null;
let freteGratis = 99.00; 

document.addEventListener('DOMContentLoaded', function() {
    carregarCarrinho();
    atualizarResumo();
    carregarProdutosRelacionados();
    // ensure badges reflect localStorage
    refreshBadges();
});

function carregarCarrinho() {
    const container = document.getElementById('itensLista');
    const carrinhoVazio = document.getElementById('carrinhoVazio');
    
    if (carrinhoAtual.length === 0) {
        carrinhoVazio.style.display = 'block';
        container.style.display = 'none';
        document.getElementById('btnFinalizar').disabled = true;
        return;
    }
    refreshBadges();
    
    carrinhoVazio.style.display = 'none';
    container.style.display = 'block';
    container.innerHTML = '';
    
    carrinhoAtual.forEach((item, index) => {
        const itemElement = criarElementoItem(item, index);
        container.appendChild(itemElement);
    });
    
    document.getElementById('btnFinalizar').disabled = false;
}


function criarElementoItem(item, index) {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'item-carrinho';
    itemDiv.dataset.index = index;
    
 
    const temDesconto = item.precoOriginal && item.precoOriginal > item.preco;
    const percentualDesconto = temDesconto ? 
        Math.round(((item.precoOriginal - item.preco) / item.precoOriginal) * 100) : 0;
    
    itemDiv.innerHTML = `
        <div class="item-imagem">
            <img src="${item.imagem}" alt="${item.nome}">
            ${temDesconto ? `<span class="desconto-badge">-${percentualDesconto}%</span>` : ''}
        </div>
        
        <div class="item-info">
            <div class="item-nome">${item.nome}</div>
            <div class="item-loja">Vendido por: ${item.loja}</div>
            <div class="item-detalhes">
                <small>Produto sustentável • Entrega rápida</small>
            </div>
        </div>
        
        <div class="item-preco">
            <div class="preco-atual">R$ ${item.preco.toFixed(2).replace('.', ',')}</div>
            ${item.precoOriginal ? 
                `<div class="preco-original">R$ ${item.precoOriginal.toFixed(2).replace('.', ',')}</div>` : ''
            }
        </div>
        
        <div class="item-quantidade">
            <button class="quantidade-btn" onclick="alterarQuantidade(${index}, -1)" ${item.quantidade <= 1 ? 'disabled' : ''}>
                <i class="fas fa-minus"></i>
            </button>
            <input type="number" class="quantidade-input" value="${item.quantidade}" 
                   min="1" max="10" onchange="definirQuantidade(${index}, this.value)">
            <button class="quantidade-btn" onclick="alterarQuantidade(${index}, 1)" ${item.quantidade >= 10 ? 'disabled' : ''}>
                <i class="fas fa-plus"></i>
            </button>
        </div>
        
        <div class="item-acoes">
            <button class="btn-favoritar" onclick="moverParaFavoritos(${index})" title="Mover para favoritos">
                <i class="fas fa-heart"></i>
            </button>
            <button class="btn-remover" onclick="removerItem(${index})" title="Remover item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    return itemDiv;
}


function alterarQuantidade(index, mudanca) {
    if (index >= 0 && index < carrinhoAtual.length) {
        const novaQuantidade = carrinhoAtual[index].quantidade + mudanca;
        
        if (novaQuantidade >= 1 && novaQuantidade <= 10) {
            carrinhoAtual[index].quantidade = novaQuantidade;
            salvarCarrinho();
            atualizarItemVisual(index);
            atualizarResumo();
            mostrarMensagem('Quantidade atualizada!', 'sucesso');
        }
    }
}


function definirQuantidade(index, novaQuantidade) {
    novaQuantidade = parseInt(novaQuantidade);
    
    if (novaQuantidade >= 1 && novaQuantidade <= 10) {
        carrinhoAtual[index].quantidade = novaQuantidade;
        salvarCarrinho();
        atualizarItemVisual(index);
        atualizarResumo();
    } else {
       
        const input = document.querySelector(`[data-index="${index}"] .quantidade-input`);
        input.value = carrinhoAtual[index].quantidade;
        mostrarMensagem('Quantidade deve ser entre 1 e 10', 'erro');
    }
}

function atualizarItemVisual(index) {
    const itemElement = document.querySelector(`[data-index="${index}"]`);
    if (itemElement) {
        const item = carrinhoAtual[index];
        
        
        const btnMenos = itemElement.querySelector('.quantidade-btn');
        const btnMais = itemElement.querySelectorAll('.quantidade-btn')[1];
        const input = itemElement.querySelector('.quantidade-input');
        
        btnMenos.disabled = item.quantidade <= 1;
        btnMais.disabled = item.quantidade >= 10;
        input.value = item.quantidade;
        
       
        itemElement.classList.add('loading');
        setTimeout(() => {
            itemElement.classList.remove('loading');
        }, 300);
    }
}


function removerItem(index) {
    if (confirm('Tem certeza que deseja remover este item?')) {
        const itemElement = document.querySelector(`[data-index="${index}"]`);
        
     
        itemElement.classList.add('removendo');
        
        setTimeout(() => {
            carrinhoAtual.splice(index, 1);
            salvarCarrinho();
            carregarCarrinho();
            atualizarResumo();
            atualizarContadores();
            mostrarMensagem('Item removido do carrinho', 'aviso');
        }, 300);
    }
}


function moverParaFavoritos(index) {
    const item = carrinhoAtual[index];
    
  
    let favoritos = JSON.parse(localStorage.getItem('favoritos')) || [];
    if (!favoritos.includes(item.id)) {
        favoritos.push(item.id);
        localStorage.setItem('favoritos', JSON.stringify(favoritos));
    }
    
   
    carrinhoAtual.splice(index, 1);
    salvarCarrinho();
    carregarCarrinho();
    atualizarResumo();
    atualizarContadores();
    
    mostrarMensagem('Item movido para favoritos!', 'sucesso');
}


function limparCarrinho() {
    if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
        carrinhoAtual = [];
        cupomAplicado = null;
        salvarCarrinho();
        carregarCarrinho();
        atualizarResumo();
        atualizarContadores();
        mostrarMensagem('Carrinho limpo!', 'aviso');
    }
}


function aplicarCupom() {
    const cupomInput = document.getElementById('cupomInput');
    const cupom = cupomInput.value.trim().toUpperCase();
    
    if (!cupom) {
        mostrarMensagem('Digite um cupom válido', 'erro');
        return;
    }
    

    const cuponsValidos = {
        'ECO10': { desconto: 0.10, minimo: 100, tipo: 'percentual' },
        'PRIMEIRA': { desconto: 0.15, minimo: 0, tipo: 'percentual' },
        'VERDE20': { desconto: 20, minimo: 150, tipo: 'fixo' },
        'SUSTENTA': { desconto: 0.08, minimo: 80, tipo: 'percentual' },
        'AMOMINHAEX': { desconto: 1.0, minimo: 1, tipo: 'percentual' }

    };
    
    const cupomData = cuponsValidos[cupom];
    const subtotal = calcularSubtotal();
    
    if (!cupomData) {
        mostrarMensagem('Cupom inválido', 'erro');
        return;
    }
    
    if (subtotal < cupomData.minimo) {
        mostrarMensagem(`Pedido mínimo de R$ ${cupomData.minimo.toFixed(2).replace('.', ',')} para este cupom`, 'erro');
        return;
    }
    
    cupomAplicado = { codigo: cupom, ...cupomData };
    cupomInput.value = '';
    atualizarResumo();
    mostrarMensagem(`Cupom ${cupom} aplicado com sucesso!`, 'sucesso');
}

function calcularSubtotal() {
    return carrinhoAtual.reduce((total, item) => {
        return total + (item.preco * item.quantidade);
    }, 0);
}

function calcularDesconto(subtotal) {
    if (!cupomAplicado) return 0;
    
    if (cupomAplicado.tipo === 'percentual') {
        return subtotal * cupomAplicado.desconto;
    } else {
        return cupomAplicado.desconto;
    }
}

function calcularFrete(subtotal) {
    return subtotal >= freteGratis ? 0 : 12.90;
}

function atualizarResumo() {
    const subtotal = calcularSubtotal();
    const desconto = calcularDesconto(subtotal);
    const frete = calcularFrete(subtotal);
    const total = subtotal - desconto + frete;
    
    document.getElementById('subtotal').textContent = formatarPreco(subtotal);
    document.getElementById('valorFrete').textContent = frete === 0 ? 'Grátis' : formatarPreco(frete);
    document.getElementById('valorTotal').textContent = formatarPreco(total);
   
    const linhaDesconto = document.getElementById('linhaDesconto');
    if (desconto > 0) {
        linhaDesconto.style.display = 'flex';
        document.getElementById('valorDesconto').textContent = '-' + formatarPreco(desconto);
    } else {
        linhaDesconto.style.display = 'none';
    }
    
    atualizarProgressoFrete(subtotal);
}

function atualizarProgressoFrete(subtotal) {
    const valorParaFrete = Math.max(0, freteGratis - subtotal);
    const progresso = Math.min(100, (subtotal / freteGratis) * 100);
    
    document.getElementById('valorParaFrete').textContent = formatarPreco(valorParaFrete);
    document.getElementById('progressoFrete').style.width = progresso + '%';
    
    if (valorParaFrete === 0) {
        document.querySelector('.sugestoes-card p').innerHTML = '🎉 <strong>Parabéns! Você ganhou frete grátis!</strong>';
    }
}

function finalizarCompra() {
    if (carrinhoAtual.length === 0) {
        mostrarMensagem('Adicione itens ao carrinho primeiro', 'erro');
        return;
    }
    
    const subtotal = calcularSubtotal();
    const desconto = calcularDesconto(subtotal);
    const frete = calcularFrete(subtotal);
    const total = subtotal - desconto + frete;
    
    const pedido = {
        itens: carrinhoAtual,
        subtotal: subtotal,
        desconto: desconto,
        frete: frete,
        total: total,
        cupom: cupomAplicado,
        timestamp: new Date().toISOString()
    };
  
    localStorage.setItem('pedidoAtual', JSON.stringify(pedido));
    mostrarMensagem('Redirecionando para o pagamento...', 'sucesso');
    
    setTimeout(() => {
        window.location.href = 'checkout.php';
    }, 2000);
}
function carregarProdutosRelacionados() {
    const container = document.querySelector('.produtos-relacionados-grid');
    const produtosRelacionados = [
        {
            id: 10,
            nome: 'Escova de Bambu',
            preco: 19.90,
            imagem: 'https://images.pexels.com/photos/4099121/pexels-photo-4099121.jpeg?auto=compress&cs=tinysrgb&w=300',
            loja: 'EcoVida',
            rating: 4.7
        },
        {
            id: 11,
            nome: 'Pote de Vidro Reutilizável',
            preco: 24.90,
            imagem: 'https://images.pexels.com/photos/1268855/pexels-photo-1268855.jpeg?auto=compress&cs=tinysrgb&w=300',
            loja: 'GreenStore',
            rating: 4.8
        },
        {
            id: 12,
            nome: 'Absorvente Reutilizável',
            preco: 35.90,
            imagem: 'https://images.pexels.com/photos/4041392/pexels-photo-4041392.jpeg?auto=compress&cs=tinysrgb&w=300',
            loja: 'BioNatura',
            rating: 4.9
        }
    ];
    
    container.innerHTML = '';
    
    produtosRelacionados.forEach(produto => {
        const produtoElement = document.createElement('div');
        produtoElement.className = 'produto-card';
        produtoElement.innerHTML = `
            <div class="produto-imagem">
                <img src="${produto.imagem}" alt="${produto.nome}">
                <button class="btn-favorito" onclick="toggleFavorito(${produto.id})">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            <div class="produto-info">
                <h3>${produto.nome}</h3>
                <p class="loja-nome">${produto.loja}</p>
                <div class="rating">
                    ${criarEstrelas(produto.rating)}
                    <span>(${produto.rating})</span>
                </div>
                <div class="preco-container">
                    <span class="preco">R$ ${produto.preco.toFixed(2).replace('.', ',')}</span>
                </div>
                <button class="btn-carrinho" onclick="adicionarCarrinhoRapido(${produto.id})">
                    Adicionar ao Carrinho
                </button>
            </div>
        `;
        
        container.appendChild(produtoElement);
    });
}

function criarEstrelas(rating) {
    let estrelas = '';
    for (let i = 1; i <= 5; i++) {
        estrelas += i <= Math.floor(rating) ? 
            '<i class="fas fa-star"></i>' : 
            '<i class="far fa-star"></i>';
    }
    return estrelas;
}

function adicionarCarrinhoRapido(produtoId) {
    const produtosDados = {
        10: { nome: 'Escova de Bambu', preco: 19.90, imagem: 'https://images.pexels.com/photos/4099121/pexels-photo-4099121.jpeg?auto=compress&cs=tinysrgb&w=300', loja: 'EcoVida' },
        11: { nome: 'Pote de Vidro Reutilizável', preco: 24.90, imagem: 'https://images.pexels.com/photos/1268855/pexels-photo-1268855.jpeg?auto=compress&cs=tinysrgb&w=300', loja: 'GreenStore' },
        12: { nome: 'Absorvente Reutilizável', preco: 35.90, imagem: 'https://images.pexels.com/photos/4041392/pexels-photo-4041392.jpeg?auto=compress&cs=tinysrgb&w=300', loja: 'BioNatura' }
    };
    
    const produto = produtosDados[produtoId];
    if (!produto) return;
    
    const itemExistente = carrinhoAtual.find(item => item.id === produtoId);
    
    if (itemExistente) {
        itemExistente.quantidade += 1;
    } else {
        carrinhoAtual.push({
            id: produtoId,
            nome: produto.nome,
            preco: produto.preco,
            imagem: produto.imagem,
            loja: produto.loja,
            quantidade: 1
        });
    }
    
    salvarCarrinho();
    carregarCarrinho();
    atualizarResumo();
    atualizarContadores();
    mostrarMensagem('Produto adicionado ao carrinho!', 'sucesso');
    refreshBadges();
}
function salvarCarrinho() {
    
    localStorage.setItem('carrinho', JSON.stringify(carrinhoAtual));
    // keep top-level carrinho in sync and refresh badges
    try {
        carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    } catch (e) {
        carrinho = [];
    }
    if (typeof refreshBadges === 'function') {
        refreshBadges();
    }
}
function atualizarContadores() {
    if (window.atualizarContadores) {
        window.atualizarContadores();
    }
    const contadorCarrinho = document.getElementById('contadorCarrinho');
    if (contadorCarrinho) {
        contadorCarrinho.textContent = carrinhoAtual.length;
    }
}
function formatarPreco(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}


function mostrarMensagem(texto, tipo = 'sucesso') {
    if (window.mostrarMensagem) {
        window.mostrarMensagem(texto, tipo);
    } else {
        alert(texto);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const cupomSugestoes = document.querySelectorAll('.cupom-sugestoes small');
    cupomSugestoes.forEach(sugestao => {
        sugestao.addEventListener('click', function() {
            const cupom = this.textContent.split(' ')[1];
            document.getElementById('cupomInput').value = cupom;
            aplicarCupom();
        });
    });
});
function autoSave() {
    salvarCarrinho();
    atualizarResumo();
}
let autoSaveTimeout;
function debounceAutoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(autoSave, 500);
}
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantidade-input')) {
        const valor = parseInt(e.target.value);
        if (isNaN(valor) || valor < 1) {
            e.target.value = 1;
        } else if (valor > 10) {
            e.target.value = 10;
        }
        debounceAutoSave();
    }
});
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        finalizarCompra();
    }
    if (e.key === 'Escape') {
        const cupomInput = document.getElementById('cupomInput');
        if (document.activeElement === cupomInput) {
            cupomInput.value = '';
        }
    }
});
window.addEventListener('beforeunload', function(e) {
    if (carrinhoAtual.length > 0) {
        e.preventDefault();
        e.returnValue = 'Você tem itens no carrinho. Tem certeza que deseja sair?';
    }
});
window.addEventListener('storage', function(e) {
    if (e.key === 'carrinho') {
        carrinhoAtual = JSON.parse(e.newValue) || [];
        carregarCarrinho();
        atualizarResumo();
    }
});