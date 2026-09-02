(function () {
    const openBtn = document.getElementById('openAddAgenda');
    const cancelBtn = document.getElementById('cancelAddAgenda');
    const modal = document.getElementById('addAgendaModal');
    if (openBtn && modal) {
        openBtn.addEventListener('click', () => modal.classList.add('is-open'));
    }
    if (cancelBtn && modal) {
        cancelBtn.addEventListener('click', () => modal.classList.remove('is-open'));
    }
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('is-open');
        });
    }
})();

(function () {
    const openBtn = document.getElementById('openAddItem');
    const cancelBtn = document.getElementById('cancelAddItem');
    const modal = document.getElementById('addItemModal');
    if (openBtn && modal) {
        openBtn.addEventListener('click', () => modal.classList.add('is-open'));
    }
    if (cancelBtn && modal) {
        cancelBtn.addEventListener('click', () => modal.classList.remove('is-open'));
    }
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('is-open');
        });
    }
})();

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.is-open').forEach((m) => m.classList.remove('is-open'));
    }
});

(function () {
    const input = document.getElementById('searchInput');
    const grid = document.getElementById('agendaGrid');
    if (!input || !grid) return;
    input.addEventListener('input', () => {
        const q = input.value.trim().toLowerCase();
        grid.querySelectorAll('.agenda-card').forEach((card) => {
            const title = (card.getAttribute('data-title') || '').toLowerCase();
            card.style.display = title.includes(q) ? '' : 'none';
        });
    });
})();
