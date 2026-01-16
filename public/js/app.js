let currentPage = 1;

async function loadRecs(page = 1) {
    try {
        const response = await fetch(`/api/recs?page=${page}`);
        const data = await response.json();

        currentPage = data.currentPage;
        renderUsers(data.data);
        renderPagination(data.currentPage, data.totalPages);
    } catch (error) {
        console.error('Failed to load users', error);
    }
}


/* RENDER USERS */
function renderUsers(recs) {
    const container = document.getElementById('userCards');
    container.innerHTML = '';

    recs.forEach(rec => {
        const card = document.createElement('div');
        card.className = 'card';

        card.innerHTML = `
            <img src="${rec.avatar}" class="card-avatar">
            <div class="card-info">${rec.first_name} ${rec.last_name}, ${rec.age}</div>
            <div class="card-status">${rec.status_label}</div>
            <div class="card-actions">
                <button class="dislike" data-id="${rec.id}">✖</button>
                <button class="super" data-id="${rec.id}">★</button>
                <button class="like" data-id="${rec.id}">❤</button>
            </div>
        `;

        container.appendChild(card);
    });

    attachActions();
}

/* ACTIONS */
function attachActions() {
    document.querySelectorAll('.like').forEach(btn => {
        btn.onclick = () => action(btn.dataset.id, 'like');
    });

    document.querySelectorAll('.dislike').forEach(btn => {
        btn.onclick = () => action(btn.dataset.id, 'dislike');
    });

    document.querySelectorAll('.super').forEach(btn => {
        btn.onclick = () => action(btn.dataset.id, 'super');
    });
}

function action(userId, type) {
    console.log(`User ${userId}: ${type}`);
}

/* PAGINATION */
function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';

    const prev = document.createElement('button');
    prev.textContent = 'Prev';
    prev.disabled = current === 1;
    prev.onclick = () => loadRecs(current - 1);
    container.appendChild(prev);

    for (let i = current - 1; i <= current + 1; i++) {
        if (i < 1 || i > total) continue;

        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'page' + (i === current ? ' active' : '');
        btn.onclick = () => loadRecs(i);
        container.appendChild(btn);
    }

    const next = document.createElement('button');
    next.textContent = 'Next';
    next.disabled = current === total;
    next.onclick = () => loadRecs(current + 1);
    container.appendChild(next);
}

/* INIT */
document.addEventListener('DOMContentLoaded', () => {
    loadRecs(1);
});
