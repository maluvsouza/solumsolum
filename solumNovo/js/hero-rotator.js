(function() {
  // elementos
  const section = document.querySelector('.hero-dynamic');
  const list = document.querySelector('.rotator__list');
  const items = Array.from(document.querySelectorAll('.rotator__item'));
  const prevBtn = document.querySelector('.rotator__nav--prev');
  const nextBtn = document.querySelector('.rotator__nav--next');
  const circle = document.getElementById('circleContent');
  const modal = document.getElementById('heroModal');
  const modalBody = document.getElementById('heroModalBody');
  const modalClose = document.querySelector('.hero-modal__close');

  if (!list || items.length === 0) return;

  let index = 0;
  const itemHeight = items[0].offsetHeight + 8;
  let interval = null;
  const ROT_MS = 3800;

  // render content into circle depending on data-type
  function renderCircleFor(item) {
    const type = item.dataset.type || 'info';
    const title = item.dataset.title || 'Informação';
    const meta = item.dataset.meta ? JSON.parse(item.dataset.meta) : null;

    let html = '';

    if (type === 'desafio') {
      html = `
        <div class="circle-card circle-card--desafio">
          <svg class="card-illustr" viewBox="0 0 120 120" aria-hidden="true">
            <rect x="8" y="8" width="104" height="104" rx="18" fill="#fff6ee"></rect>
            <path d="M60 36 v36" stroke="#d98a00" stroke-width="6" stroke-linecap="round"/>
            <circle cx="60" cy="36" r="10" fill="#f6a85b"/>
          </svg>
          <h4>${title}</h4>
          <p class="muted">Complete este pequeno hábito hoje e marque como feito.</p>
          <a href="#" class="btn primary" data-action="do-challenge">Quero participar</a>
        </div>`;
    } else if (type === 'dica') {
      html = `
        <div class="circle-card circle-card--dica">
          <svg class="card-illustr" viewBox="0 0 120 120" aria-hidden="true">
            <rect x="8" y="8" width="104" height="104" rx="18" fill="#f0fff0"></rect>
            <path d="M30 84 L60 40 L90 84 Z" fill="#6bbf67" opacity="0.95"></path>
          </svg>
          <h4>${title}</h4>
          <p class="muted">Pequena mudança, grande impacto — veja sugestões de produtos.</p>
          <a href="#" class="btn outline" data-action="see-products">Ver sugestões</a>
        </div>`;
    } else if (type === 'quiz') {
      html = `
        <div class="circle-card circle-card--quiz">
          <svg class="card-illustr" viewBox="0 0 120 120" aria-hidden="true">
            <rect x="8" y="8" width="104" height="104" rx="18" fill="#f4faff"></rect>
            <circle cx="60" cy="38" r="10" fill="#6ca3e8"></circle>
            <rect x="34" y="66" width="52" height="8" rx="4" fill="#6ca3e8"></rect>
          </svg>
          <h4>${title}</h4>
          <p class="muted">Responda 3 perguntas e receba recomendações.</p>
          <a href="#" class="btn primary" data-action="start-quiz">Começar</a>
        </div>`;
    } else if (type === 'loja') {
      html = `
        <div class="circle-card circle-card--loja">
          <svg class="card-illustr" viewBox="0 0 120 120" aria-hidden="true">
            <rect x="8" y="8" width="104" height="104" rx="18" fill="#fff7e8"></rect>
            <rect x="26" y="32" width="68" height="44" rx="6" fill="#e7c27a"></rect>
            <rect x="34" y="82" width="52" height="8" rx="4" fill="#fff"></rect>
          </svg>
          <h4>${title}</h4>
          <p class="muted">Avaliação: ${meta?.rating || '—'} · Produtos: ${meta?.products || '—'}</p>
          <a href="#" class="btn outline" data-action="open-store">Visitar loja</a>
        </div>`;
    } else {
      html = `
        <div class="circle-card circle-card--info">
          <svg class="card-illustr" viewBox="0 0 120 120" aria-hidden="true">
            <rect x="8" y="8" width="104" height="104" rx="18" fill="#fdecdc"></rect>
            <circle cx="60" cy="40" r="18" fill="#f6a6bf"></circle>
            <rect x="26" y="70" width="68" height="10" rx="4" fill="#d9a35a"></rect>
          </svg>
          <h4>${title}</h4>
          <p class="muted">Clique num item ao lado para ver mais.</p>
        </div>`;
    }

    circle.innerHTML = html;

    section.classList.add('plant-open');
    clearTimeout(section._plantTimer);
    section._plantTimer = setTimeout(() => {
      section.classList.remove('plant-open');
    }, 4500);
  }

  function showAt(i) {
    index = (i + items.length) % items.length;
    list.style.transform = `translateY(${-index * itemHeight}px)`;
    items.forEach((it, idx) => it.classList.toggle('active', idx === index));
    renderCircleFor(items[index]);
  }

  function next() {
    showAt(index + 1);
  }

  function prev() {
    showAt(index - 1);
  }

  function startAuto() {
    stopAuto();
    interval = setInterval(next, ROT_MS);
  }

  function stopAuto() {
    if (interval) clearInterval(interval);
    interval = null;
  }

  showAt(0);
  startAuto();

  prevBtn?.addEventListener('click', () => {
    prev();
    startAuto();
  });
  nextBtn?.addEventListener('click', () => {
    next();
    startAuto();
  });

  items.forEach((it, idx) => {
    it.addEventListener('click', () => {
      showAt(idx);
      stopAuto();
    });
    it.addEventListener('keydown', (e) => {
      if (['Enter', ' '].includes(e.key)) {
        e.preventDefault();
        it.click();
      }
    });
  });

  section.addEventListener('click', (e) => {
    const action = e.target.dataset?.action;
    if (!action) return;
    e.preventDefault();

    if (action === 'do-challenge') {
      modalBody.innerHTML = `<h3>Participe do desafio</h3><p>Obrigado por aceitar! Veja produtos relacionados abaixo.</p><p><a href="#" class="btn primary">Produtos</a></p>`;
    } else if (action === 'see-products') {
      modalBody.innerHTML = `<h3>Sugestões</h3><p>Lista de produtos relacionados à dica.</p><p><a href="#" class="btn outline">Ver todos</a></p>`;
    } else if (action === 'start-quiz') {
      modalBody.innerHTML = `<h3>Quiz</h3><p>Começando quiz rápido… (exemplo)</p>`;
    } else if (action === 'open-store') {
      modalBody.innerHTML = `<h3>Loja</h3><p>Detalhes da loja e produtos em destaque.</p><p><a href="#" class="btn outline">Visitar loja</a></p>`;
    }

    modal.setAttribute('aria-hidden', 'false');
    stopAuto();
  });

  modalClose?.addEventListener('click', () => {
    modal.setAttribute('aria-hidden', 'true');
    startAuto();
  });

  modal.addEventListener('click', (ev) => {
    if (ev.target === modal) {
      modal.setAttribute('aria-hidden', 'true');
      startAuto();
    }
  });

  section.addEventListener('mouseenter', stopAuto);
  section.addEventListener('mouseleave', startAuto);
  section.addEventListener('focusin', stopAuto);
  section.addEventListener('focusout', startAuto);

  window.addEventListener('resize', () => {
    const newH = items[0].offsetHeight + 8;
    if (newH !== itemHeight) showAt(index);
  });

})();
