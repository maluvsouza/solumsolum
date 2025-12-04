document.addEventListener('DOMContentLoaded', function () {
  syncCartCounter();
  window.addEventListener('storage', function () {
    syncCartCounter();
  });

  const originalRefreshBadges = window.refreshBadges;
  if (originalRefreshBadges) {
    window.refreshBadges = function () {
      originalRefreshBadges();
      syncCartCounter();
    };
  }
});


function syncCartCounter() {
  const desktopCounter = document.getElementById('contadorCarrinho');
  const mobileCounter = document.getElementById('mobile-cart-badge');

  if (desktopCounter && mobileCounter) {
    const count = desktopCounter.innerText || '0';

    if (count !== '0') {
      mobileCounter.innerText = count;
      mobileCounter.style.display = 'block';
    } else {
      mobileCounter.style.display = 'none';
    }
  }
}

function updateCartBadge(count) {
  const desktopCounter = document.getElementById('contadorCarrinho');
  const mobileCounter = document.getElementById('mobile-cart-badge');

  if (desktopCounter) {
    desktopCounter.innerText = count;
  }

  if (mobileCounter) {
    if (count > 0) {
      mobileCounter.innerText = count;
      mobileCounter.style.display = 'block';
    } else {
      mobileCounter.style.display = 'none';
    }
  }

  try {
    const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
    if (cart.length > 0) {
      mobileCounter.innerText = cart.length;
      mobileCounter.style.display = 'block';
    } else {
      mobileCounter.style.display = 'none';
    }
  } catch (e) {
    console.error('Erro ao atualizar badge:', e);
  }
}

syncCartCounter();

