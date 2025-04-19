let editingUECode = null;
let editingUserId = null;
let assignedUEs = [];

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
        } else {
            isAdminCheckbox.disabled = false;
        }
    });

    // --- Ajouter / Modifier une UE ---
    ueForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const code = document.getElementById('ueCode').value.trim();
        const nom = document.getElementById('ueNom').value.trim();
        const desc = document.getElementById('ueDesc').value.trim();
        const respo = document.getElementById('ueRespo').value.trim();

        if (!code || !nom) return;

        const ueData = { code, nom, description: desc, responsable_ue: respo };

        const url = editingUECode ? '/admin/modifier-ue' : '/admin/ajouter-ue';
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(ueData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Erreur lors de l'enregistrement de l'UE.");
            });

        const modal = bootstrap.Modal.getInstance(document.getElementById('ueModal'));
        modal.hide();
    });

    // --- Ajouter / Modifier un utilisateur ---
    userForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const prenom = document.getElementById('prenom').value.trim();
        const nom = document.getElementById('nom').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const role = document.getElementById('role').value;
        const isAdmin = document.getElementById('isAdmin').checked;

        const userData = { prenom, nom, email, password, role, isAdmin, ues: assignedUEs };

        fetch('/admin/ajouter-utilisateur', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Erreur lors de l'enregistrement de l'utilisateur.");
            });

        const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
        modal.hide();
    });

    // --- Sélection des UEs (badges) ---
    document.getElementById('ueSelect').addEventListener('change', function (e) {
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
});

// --- Édition d’une UE ---
function editUE(ue) {
    const modal = new bootstrap.Modal(document.getElementById('ueModal'));
    editingUECode = ue.code;

    document.getElementById('ueForm').reset();
    document.getElementById('ueCode').value = ue.code;
    document.getElementById('ueNom').value = ue.nom;
    document.getElementById('ueDesc').value = ue.description;
    document.getElementById('ueRespo').value = ue.responsable_ue || '';

    modal.show();
}

// --- Suppression d’une UE ---
function deleteUE(code) {
    if (confirm("Supprimer cette UE ?")) {
        fetch(`/admin/supprimer-ue/${code}`, { method: 'DELETE' })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Erreur lors de la suppression.");
            });
    }
}

// --- Édition d’un utilisateur ---
function editUser(user) {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    editingUserId = user.id;
    assignedUEs = user.ues || [];

    document.getElementById('userForm').reset();
    document.getElementById('prenom').value = user.prenom;
    document.getElementById('nom').value = user.nom;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    document.getElementById('isAdmin').checked = user.admin;

    document.getElementById('ueBadges').innerHTML = '';
    user.ues.forEach(ueCode => {
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
        fetch(`/admin/supprimer-utilisateur/${id}`, { method: 'DELETE' })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Erreur lors de la suppression.");
            });
    }
}
