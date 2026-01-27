let currentPage = 1;

async function loadRecs(page = 1) {
    try {
        const response = await fetch(`/api/recs?page=${page}`);
        const data = await response.json();

        page = data.page;

        renderUsers(data.data);
        renderPagination(data.page, data.length);
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
            <div class="card-info">(#${rec.user_id})${rec.first_name} ${rec.last_name}, ${rec.age}</div>
            <div class="card-status">${rec.status_label}</div>
            <div class="card-actions">
                <button class="dislike" data-user-id="${rec.user_id}">✖</button>
                <button class="super" data-user-id="${rec.user_id}">★</button>
                <button class="like" data-user-id="${rec.user_id}">❤</button>
            </div>
        `;

        container.appendChild(card);
    });

    attachActions();
}

/* ACTIONS */
function attachActions() {
    document.querySelectorAll('.like').forEach(btn => {
        btn.onclick = () => action(btn.dataset.userId, 'like', btn);
    });

    document.querySelectorAll('.dislike').forEach(btn => {
        btn.onclick = () => action(btn.dataset.userId, 'dislike', btn);
    });

    document.querySelectorAll('.super').forEach(btn => {
        btn.onclick = () => action(btn.dataset.userId, 'super', btn);
    });
}

function action(userId, type, btn) {
    const cardBlock = btn.closest('div').parentElement;
    cardBlock.classList.add(type + '-clicked');

    console.log(`User ${userId}: ${type}`);

    const response = fetch('/api/reactions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            target_user_id: Number(userId),
            type: type
        })
      });
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

    const next = document.createElement('button');
    next.textContent = 'Next';
    next.disabled = total === 0;
    next.onclick = () => loadRecs(current + 1);
    container.appendChild(next);
}

/* INIT */
document.addEventListener('DOMContentLoaded', () => {
    loadRecs(1);
});
