/**
 * Front-End – Leitura e Edição (dados vêm do PHP)
 */

// --- DOM ---
const recordsTableBody = document.getElementById('recordsTableBody');
const searchInput = document.getElementById('searchCarro');
const filterStatus = document.getElementById('filterStatus');
const filterGravacao = document.getElementById('filterGravacao');
const filterDateStart = document.getElementById('filterDateStart');
const filterDateEnd = document.getElementById('filterDateEnd');

let editingId = null;

// --- INIT ---
document.addEventListener('DOMContentLoaded', () => {
    renderRecords();

    [searchInput, filterStatus, filterGravacao, filterDateStart, filterDateEnd]
        .forEach(el => el && el.addEventListener('input', renderRecords));
});

// --- LISTAR ---
async function renderRecords() {
    const params = new URLSearchParams({
        search: searchInput.value,
        status: filterStatus.value,
        gravacao: filterGravacao.value,
        date_start: filterDateStart.value,
        date_end: filterDateEnd.value
    });

    const response = await fetch(`../api/read.php?action=list&${params}`);
    const result = await response.json();

    recordsTableBody.innerHTML = '';

    if (!result.success || result.data.length === 0) {
        recordsTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    Nenhum registro encontrado
                </td>
            </tr>
        `;
        return;
    }

    result.data.forEach(r => {
        recordsTableBody.innerHTML += `
            <tr>
                <td>${formatDate(r.data)}</td>
                <td>#${r.carro}</td>

                <td>
                    ${r.gravacao == 1 ? '🎤 Sim' : '🔇 Não'}
                </td>

                <td>
                    ${r.trocado == 1 ? '🟢 Trocado' : '🔴 Não trocado'}
                </td>

                <td class="text-end">
                    <button class="btn btn-sm btn-light"
                        onclick="editRecord(${r.id})">
                        ✏️
                    </button>
                </td>
            </tr>
        `;
    });
}

// --- EDITAR ---
async function editRecord(id) {
    const response = await fetch(`../api/records.php?action=get&id=${id}`);
    const result = await response.json();

    if (!result.success) {
        alert('Erro ao carregar registro');
        return;
    }

    const r = result.data;
    editingId = r.id;

    document.getElementById('carroInput').value = r.carro;
    document.getElementById('dataRegistro').value = r.data;

    document.querySelector(`input[name="gravacao"][value="${r.gravacao}"]`).checked = true;
    document.querySelector(`input[name="trocado"][value="${r.trocado}"]`).checked = true;

    document.getElementById('submitBtn').innerText = 'Atualizar';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// --- HELPER ---
function formatDate(dateStr) {
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}
