let editingUECode = null;
let editingUserId = null;
let assignedUEs = [];

function escapeStringForJS(str) {
    return str.replace(/['"\\]/g, '');
}

document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const isAdminCheckbox = document.getElementById('isAdmin');
    const userForm = document.getElementById('userForm');
    const ueForm = document.getElementById('ueForm');

    // --- Rôle & Admin checkbox ---
    roleSelect.addEventListener('change', function () {
        if (this.value === 'admin') {
            isAdminCheckbox.checked = true;
            isAdminCheckbox.disabled = true;
        } else if (this.value === 'étudiant') {
            isAdminCheckbox.checked = false;
            isAdminCheckbox.disabled = true;
        } else if (this.value === 'prof') {
            isAdminCheckbox.disabled = false;
        }
    });

    // --- Ajouter / Modifier une UE ---
    ueForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const code = document.getElementById('ueCode').value.trim();
        const nom = escapeStringForJS(document.getElementById('ueNom').value.trim());
        const desc = escapeStringForJS(document.getElementById('ueDesc').value.trim());
        const respo = document.getElementById('ueRespo').value.trim();
        const img = document.getElementById('ueImage').value;

        if (!code || !nom) return;

        if (respo === '') {
            alert("Veuillez sélectionner un responsable pour l'UE.");
            return;
        }

        const ueData = { code, nom, description: desc, responsable_ue: respo, image: img };
        const url = editingUECode ? '/admin/modifier-ue' : '/admin/ajouter-ue';

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(ueData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || "Erreur lors de l'enregistrement de l'UE.");
            });

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('ueModal'));
        modal.hide();
    });


    // --- Ajouter / Modifier un utilisateur ---
    userForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const prenom = escapeStringForJS(document.getElementById('prenom').value.trim());
        const nom = escapeStringForJS(document.getElementById('nom').value.trim());
        const email = escapeStringForJS(document.getElementById('email').value.trim());
        const password = escapeStringForJS(document.getElementById('password').value.trim());
        const role = document.getElementById('role').value;
        const isAdmin = document.getElementById('isAdmin').checked;

        const userData = {
            id: editingUserId,
            prenom, nom, email, password, role, isAdmin,
            ues: assignedUEs
        };

        console.log(userData)

        const url = editingUserId ? '/admin/modifier-utilisateur' : '/admin/ajouter-utilisateur';

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || "Erreur lors de l'enregistrement de l'utilisateur.");
            });

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('userModal'));
        modal.hide();
    });

    // --- Sélection des UEs (badges) ---
    document.getElementById('ueSelect').addEventListener('change', function () {
        const ueCode = this.value;
        const ueName = this.options[this.selectedIndex].text;

        if (!ueCode || assignedUEs.includes(ueCode)) return;

        assignedUEs.push(ueCode);

        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary me-2';
        badge.innerHTML = `${ueName} <span class="badge-close-button" style="cursor:pointer;">×</span>`;

        badge.querySelector('.badge-close-button').onclick = () => {
            assignedUEs = assignedUEs.filter(code => code !== ueCode);
            badge.remove();
        };

        document.getElementById('ueBadges').appendChild(badge);
    });

    // Reset vars on modal close
    document.getElementById('ueModal').addEventListener('hidden.bs.modal', () => {
        editingUECode = null;
    });
    document.getElementById('userModal').addEventListener('hidden.bs.modal', () => {
        editingUserId = null;
        assignedUEs = [];
    });
});

// --- Édition d’une UE ---
function editUE(ue = null) {
    const modalEl = document.getElementById('ueModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const codeInput = document.getElementById('ueCode');

    if (ue) { // MODIFICATION
        editingUECode = ue.code;
        codeInput.value = ue.code ?? '';
        codeInput.readOnly = true;
        codeInput.classList.add('bg-light', 'text-muted'); // Appliquer le style
        document.getElementById('ueNom').value = ue.nom ?? '';
        document.getElementById('ueDesc').value = ue.description ?? '';
        document.getElementById('ueRespo').value = ue.responsable_ue ?? '';
    } else { // AJOUT
        editingUECode = null;
        codeInput.value = '';
        codeInput.readOnly = false;
        codeInput.classList.remove('bg-light', 'text-muted'); // Retirer le style
        document.getElementById('ueNom').value = '';
        document.getElementById('ueDesc').value = '';
        document.getElementById('ueRespo').value = '';
    }
    modal.show();
}

// --- Suppression d’une UE ---
function deleteUE(code) {
    if (confirm("Supprimer cette UE ?")) {
        fetch(`/admin/supprimer-ue/${code}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || "Erreur lors de la suppression.");
            });
    }
}

// --- Édition d’un utilisateur ---
function editUser(user) {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('userModal'));
    editingUserId = user?.id ?? null;
    assignedUEs = user?.ues ?? [];

    document.getElementById('userForm').reset();
    document.getElementById('prenom').value = user?.prenom ?? '';
    document.getElementById('nom').value = user?.nom ?? '';
    document.getElementById('email').value = user?.email ?? '';
    document.getElementById('role').value = user?.role ?? 'étudiant';
    document.getElementById('isAdmin').checked = user?.admin ?? false;

    document.getElementById('ueBadges').innerHTML = '';
    assignedUEs.forEach(ueCode => {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary me-2';
        badge.innerHTML = `${ueCode} <span class="badge-close-button" style="cursor:pointer;">×</span>`;
        badge.querySelector('.badge-close-button').onclick = () => {
            assignedUEs = assignedUEs.filter(code => code !== ueCode);
            badge.remove();
        };
        document.getElementById('ueBadges').appendChild(badge);
    });

    modal.show();
}

// --- Suppression d’un utilisateur ---
function deleteUser(id) {
    if (confirm("Supprimer cet utilisateur ?")) {
        fetch(`/admin/supprimer-utilisateur/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || "Erreur lors de la suppression.");
            });
    }
}
