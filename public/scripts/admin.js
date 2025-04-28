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
        if (this.value === 'ROLE_ADMIN') {
            isAdminCheckbox.checked = true;
            isAdminCheckbox.disabled = true;
        } else if (this.value === 'ROLE_ELEVE') {
            isAdminCheckbox.checked = false;
            isAdminCheckbox.disabled = true;
        } else if (this.value === 'ROLE_PROFESSEUR') {
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

        if (respo === '') {
            alert("Veuillez sélectionner un responsable pour l'UE.");
            return;
        }

        const data = {
            code: code,
            nom: nom,
            description: desc,
            responsable_ue: respo
        };

        const url = editingUECode ? '/admin/modifier-ue' : '/admin/ajouter-ue';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || "Erreur lors de l'enregistrement de l'UE.");
                }
            })
            .catch(err => {
                console.error('Error:', err);
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

        const selectedRole = document.getElementById('role').value;
        const isAdmin = document.getElementById('isAdmin').checked;

        let roles = [];

        if (selectedRole === 'ROLE_ELEVE') {
            roles.push('ROLE_ELEVE');
        } else if (selectedRole === 'ROLE_PROFESSEUR') {
            roles.push('ROLE_PROFESSEUR');
        }

        if (isAdmin && !roles.includes('ROLE_ADMIN')) {
            roles.push('ROLE_ADMIN');
        }

        const userData = {
            id: editingUserId,
            prenom,
            nom,
            email,
            password,
            roles, // <- Attention ici : ROLES (pluriel)
            isAdmin,
            ues: assignedUEs
        };

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
        codeInput.classList.add('bg-light', 'text-muted');
        document.getElementById('ueNom').value = ue.nom ?? '';
        document.getElementById('ueDesc').value = ue.description ?? '';
        document.getElementById('ueRespo').value = ue.responsable_ue ?? '';
    } else { // AJOUT
        editingUECode = null;
        codeInput.value = '';
        codeInput.readOnly = false;
        codeInput.classList.remove('bg-light', 'text-muted');
        document.getElementById('ueNom').value = '';
        document.getElementById('ueDesc').value = '';
        document.getElementById('ueRespo').value = '';
        document.getElementById('ueImage').value = '';
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

    const emailInput = document.getElementById('email');

    document.getElementById('userForm').reset();
    document.getElementById('prenom').value = user?.prenom ?? '';
    document.getElementById('nom').value = user?.nom ?? '';
    emailInput.value = user?.email ?? '';
    document.getElementById('role').value = user?.role ?? 'étudiant';
    document.getElementById('isAdmin').checked = user?.admin ?? false;

    if (user?.id) {
        emailInput.readOnly = true;
        emailInput.classList.add('bg-light', 'text-muted');
    } else {
        emailInput.readOnly = false;
        emailInput.classList.remove('bg-light', 'text-muted');
    }

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

// --- Reset lecture seule sur fermeture ---
document.getElementById('userModal').addEventListener('hidden.bs.modal', () => {
    editingUserId = null;
    assignedUEs = [];
    const emailInput = document.getElementById('email');
    emailInput.readOnly = false;
    emailInput.classList.remove('bg-light', 'text-muted');
});

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
