// assets/scripts/modal.js

document.addEventListener('DOMContentLoaded', function () {
    const coursCode = document.getElementById('coursCode').getAttribute('data-cours-code');

    // Switch tabs between message and file
    document.getElementById('tabMessage').addEventListener('click', function () {
        document.getElementById('formMessage').classList.remove('d-none');
        document.getElementById('formFile').classList.add('d-none');
        this.classList.add('active');
        document.getElementById('tabFile').classList.remove('active');
    });

    document.getElementById('tabFile').addEventListener('click', function () {
        document.getElementById('formFile').classList.remove('d-none');
        document.getElementById('formMessage').classList.add('d-none');
        this.classList.add('active');
        document.getElementById('tabMessage').classList.remove('active');
    });

    // Handle Message form submission (create or edit)
    document.getElementById('formMessage').addEventListener('submit', function (e) {
        e.preventDefault();

        const messageType = document.getElementById('messageType').value;
        const messageTitle = document.getElementById('messageTitle').value;
        const messageContent = document.getElementById('messageContent').value;
        const isImportant = messageType === 'Important';
        const author = 1; // Example author ID

        const editId = this.getAttribute('data-edit-id');

        if (editId) {
            // Edit existing message
            fetch(`/update-message/${editId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    title: messageTitle,
                    content: messageContent,
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                        modal.hide();
                        location.reload();
                    } else {
                        alert('Erreur lors de la modification du message.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            // Create new message
            fetch('/create-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    coursCode: coursCode,
                    title: messageTitle,
                    content: messageContent,
                    important: isImportant,
                    author: author
                })
            })
                .then(response => response.json())
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                    modal.hide();
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });

    // Handle file form submission
    document.getElementById('formFile').addEventListener('submit', function (e) {
        e.preventDefault();

        const fileTitle = document.getElementById('fileTitle').value;
        const fileDescription = document.getElementById('fileDescription').value;
        const fileInput = document.getElementById('fileUpload');
        const file = fileInput.files[0];
        const author = 1;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('fileTitle', fileTitle);
        formData.append('fileDescription', fileDescription);
        formData.append('coursCode', coursCode);
        formData.append('author', author);
        formData.append('important', false);

        fetch('/create-message-file', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                modal.hide();
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Delete message
    document.querySelectorAll('.btn-delete-message').forEach(button => {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
                fetch(`/delete-message/${messageId}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert('Erreur lors de la suppression du message.');
                        }
                    });
            }
        });
    });

// Edit message
    document.querySelectorAll('.btn-edit-message').forEach(button => {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            const currentTitle = this.dataset.title || '';
            const currentContent = this.dataset.content || '';
            const isFile = this.dataset.file === 'true';

            const modal = new bootstrap.Modal(document.getElementById('myModal'));

            if (isFile) {
                // File message
                document.getElementById('fileTitle').value = currentTitle;
                document.getElementById('fileDescription').value = currentContent;
                document.getElementById('tabFile').classList.add('active');
                document.getElementById('tabMessage').classList.remove('active');
                document.getElementById('tabFile').classList.add('disabled');
                document.getElementById('tabMessage').classList.add('disabled');
                document.getElementById('formFile').classList.remove('d-none');
                document.getElementById('formMessage').classList.add('d-none');
                document.getElementById('formFile').setAttribute('data-edit-id', messageId);
            } else {
                // Normal message
                document.getElementById('messageTitle').value = currentTitle;
                document.getElementById('messageContent').value = currentContent;
                document.getElementById('tabMessage').classList.add('active');
                document.getElementById('tabFile').classList.remove('active');
                document.getElementById('tabMessage').classList.add('disabled');
                document.getElementById('tabFile').classList.add('disabled');
                document.getElementById('formMessage').classList.remove('d-none');
                document.getElementById('formFile').classList.add('d-none');
                document.getElementById('formMessage').setAttribute('data-edit-id', messageId);
            }

            modal.show();
        });
    });
        document.getElementById('myModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formMessage').removeAttribute('data-edit-id');
        document.getElementById('formMessage').reset();
        document.getElementById('formFile').removeAttribute('data-edit-id');
        document.getElementById('formFile').reset();
        document.getElementById('tabMessage').classList.remove('disabled');
        document.getElementById('tabFile').classList.remove('disabled');
    });


});
