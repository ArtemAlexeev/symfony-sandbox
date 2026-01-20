<script>
/* =========================
   CONFIG
========================= */
const API_URL = 'https://example.com/api/users'; // change later
let currentPage = 1;

/* =========================
   LOAD USERS
========================= */
async function loadUsers(page = 1) {
    console.log('Loading page:', page);

    // --- MOCK DATA (for now) ---
    // Remove this block when real API is ready
    const response = mockApi(page);

    currentPage = response.currentPage;
    renderUsers(response.data);
    renderPagination(response.currentPage, response.totalPages);
}

/* =========================
   RENDER USERS
========================= */
function renderUsers(users) {
    const container = document.getElementById('userCards');
    container.innerHTML = '';

    users.forEach(user => {
        const card = document.createElement('div');
        card.className = 'card';

        card.innerHTML = `
            <img src="${user.avatar}" class="card-avatar">
            <div class="card-info">${user.name}, ${user.age}</div>
            <div class="card-actions">
                <button class="dislike" data-id="${user.id}">✖</button>
                <button class="super" data-id="${user.id}">★</button>
                <button class="like" data-id="${user.id}">❤</button>
            </div>
        `;

        container.appendChild(card);
    });

    attachActionEvents();
}

/* =========================
   ACTION BUTTONS
========================= */
function attachActionEvents() {
    document.querySelectorAll('.like').forEach(btn => {
        btn.onclick = () => userAction(btn.dataset.id, 'like');
    });

    document.querySelectorAll('.dislike').forEach(btn => {
        btn.onclick = () => userAction(btn.dataset.id, 'dislike');
    });

    document.querySelectorAll('.super').forEach(btn => {
        btn.onclick = () => userAction(btn.dataset.id, 'super');
    });
}

function userAction(userId, action) {
    console.log(`User ${userId}: ${action}`);

    // Later:
    // fetch(`/api/users/${userId}/${action}`, { method: 'POST' });
}

/* =========================
   PAGINATION
========================= */
function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';

    // Prev
    const prev = document.createElement('button');
    prev.textContent = 'Prev';
    prev.disabled = current === 1;
    prev.onclick = () => loadUsers(current - 1);
    container.appendChild(prev);

    // Pages (3 buttons)
    for (let i = current - 1; i <= current + 1; i++) {
        if (i < 1 || i > total) continue;

        const pageBtn = document.createElement('button');
        pageBtn.textContent = i;
        pageBtn.className = 'page' + (i === current ? ' active' : '');
        pageBtn.onclick = () => loadUsers(i);

        container.appendChild(pageBtn);
    }

    // Next
    const next = document.createElement('button');
    next.textContent = 'Next';
    next.disabled = current === total;
    next.onclick = () => loadUsers(current + 1);
    container.appendChild(next);
}

/* =========================
   MOCK API (DELETE LATER)
========================= */
function mockApi(page) {
    const users = [];
    for (let i = 1; i <= 8; i++) {
        users.push({
            id: (page - 1) * 8 + i,
            name: 'Pet ' + ((page - 1) * 8 + i),
            age: Math.floor(Math.random() * 6) + 1,
            avatar: 'https://via.placeholder.com/300'
        });
    }

    return {
        data: users,
        currentPage: page,
        totalPages: 5
    };
}

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', () => {
    loadUsers(1);
});
</script>
