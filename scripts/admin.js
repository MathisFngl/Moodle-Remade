let editingUECode = null;
let assignedUEs = [];

document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const isAdminCheckbox = document.getElementById('isAdmin');

    roleSelect.addEventListener('change', function () {
        const selectedRole = roleSelect.value;

        if (selectedRole === 'admin') {
            isAdminCheckbox.checked = true;
            isAdminCheckbox.disabled = true;
        } else if (selectedRole === 'étudiant') {
            isAdminCheckbox.checked = false;
            isAdminCheckbox.disabled = true;
        } else if (selectedRole === 'prof') {
            isAdminCheckbox.disabled = false;
        }
    });
    window.editUser = function (user) {
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        document.getElementById('userForm').reset();
        if (user) {
            document.getElementById('prenom').value = user.prenom;
            document.getElementById('nom').value = user.nom;
            document.getElementById('email').value = user.email;
            roleSelect.value = user.role;
            isAdminCheckbox.checked = user.admin;

            if (user.role === "admin") {
                isAdminCheckbox.disabled = true;
            } else if (user.role === "étudiant") {
                isAdminCheckbox.disabled = true;
            } else {
                isAdminCheckbox.disabled = false;
            }
        } else {
            document.getElementById('password').value = "1234";
            isAdminCheckbox.disabled = false;
            isAdminCheckbox.checked = false;
            roleSelect.value = "étudiant";
        }
        modal.show();
    };
});

function editUE(ue) {
    const modal = new bootstrap.Modal(document.getElementById('ueModal'));
    document.getElementById('ueForm').reset();

    editingUECode = null;

    if (ue) {
        document.getElementById('ueCode').value = ue.code;
        document.getElementById('ueNom').value = ue.nom;
        document.getElementById('ueDesc').value = ue.desc;
        editingUECode = ue.code;
    }
    modal.show();
}

document.getElementById("userForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
    modal.hide();
});

document.getElementById('ueSelect').addEventListener('change', function(e) {
    if (e.target.value) {
        const selectedUE = e.target.options[e.target.selectedIndex];
        const ueCode = selectedUE.value;
        const ueName = selectedUE.text;

        if (assignedUEs.includes(ueCode)) {
            return;
        }

        assignedUEs.push(ueCode);
        const badge = document.createElement('span');
        badge.classList.add('badge', 'bg-secondary', 'me-2');
        badge.textContent = ueName;

        const removeBtn = document.createElement('span');
        removeBtn.classList.add('badge-close-button');
        removeBtn.textContent = '×';
        removeBtn.onclick = () => {
            assignedUEs = assignedUEs.filter(code => code !== ueCode);
            badge.remove();
        };

        badge.appendChild(removeBtn);
        document.getElementById('ueBadges').appendChild(badge);
    }
});

document.getElementById('role').addEventListener('change', function () {
    const isAdminCheckbox = document.getElementById('isAdmin');
    if (this.value === 'étudiant') {
        isAdminCheckbox.checked = false;
        isAdminCheckbox.disabled = true;
    } else {
        isAdminCheckbox.disabled = false;
    }
});

document.getElementById('ueForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const code = document.getElementById('ueCode').value.trim();
    const nom = document.getElementById('ueNom').value.trim();
    const desc = document.getElementById('ueDesc').value.trim();
    const imageInput = document.getElementById('ueImage');
    const imagePath = imageInput.files.length > 0 ? URL.createObjectURL(imageInput.files[0]) : "path_to_default.jpg";

    if (!code || !nom) return;

    const ueData = {
        code,
        nom,
        desc,
        image: imagePath
    };

    const tableBody = document.querySelector('#ue table tbody');
    //modifier
    if (editingUECode) {
        const row = Array.from(tableBody.rows).find(r => r.cells[0].textContent === editingUECode);
        if (row) {
            row.cells[0].textContent = ueData.code;
            row.cells[1].textContent = ueData.nom;
            row.cells[2].textContent = ueData.desc;
            row.cells[3].innerHTML = `<img src="${ueData.image}" class="img-thumbnail" style="width: 50px;" alt="uv">`;
            row.cells[4].innerHTML = `
                    <button class="btn btn-warning btn-sm" onclick='editUE(${JSON.stringify(ueData)})'>Modifier</button>
                    <button class="btn btn-danger btn-sm">Supprimer</button>`;
        }
    } else {
        //ajouter
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
                <td>${ueData.code}</td>
                <td>${ueData.nom}</td>
                <td>${ueData.desc}</td>
                <td><img src="${ueData.image}" class="img-thumbnail" style="width: 50px;" alt="uv"></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick='editUE(${JSON.stringify(ueData)})'>Modifier</button>
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </td>
            `;
        tableBody.appendChild(newRow);
    }

    document.getElementById('ueForm').reset();
    editingUECode = null;
    const modal = bootstrap.Modal.getInstance(document.getElementById('ueModal'));
    modal.hide();
});