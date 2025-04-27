// assets/scripts/modal.js

document.addEventListener('DOMContentLoaded', function () {

    // Get the coursCode from a data attribute in the DOM
    const coursCode = document.getElementById('coursCode').getAttribute('data-cours-code');
    console.log(coursCode);

    // Handle form switch between message and file tabs
    document.getElementById('tabMessage').addEventListener('click', function () {
        document.getElementById('formMessage').classList.remove('d-none');
        document.getElementById('formFile').classList.add('d-none');
        document.getElementById('tabMessage').classList.add('active');
        document.getElementById('tabFile').classList.remove('active');
    });

    document.getElementById('tabFile').addEventListener('click', function () {
        document.getElementById('formFile').classList.remove('d-none');
        document.getElementById('formMessage').classList.add('d-none');
        document.getElementById('tabFile').classList.add('active');
        document.getElementById('tabMessage').classList.remove('active');
    });

    document.getElementById('formMessage').addEventListener('submit', function (e) {
        e.preventDefault();

        const messageType = document.getElementById('messageType').value;
        const messageTitle = document.getElementById('messageTitle').value;
        const messageContent = document.getElementById('messageContent').value;
        const isImportant = messageType === 'Important';
        const author = 1;

        console.log({
            coursCode: coursCode,
            title: messageTitle,
            content: messageContent,
            important: isImportant,
            author: author,
            file: null
        });

        fetch('/create-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                coursCode: coursCode,
                title: messageTitle,
                content: messageContent,
                file: null,  // No file
                important: isImportant,
                author: author
            })
        })
            .then(response => response.json())
            .then(data => {
                // Handle success or errors here
                console.log('Message created:', data);
                $('#myModal').modal('hide');
            })
            .catch(error => {
                console.error('Request body:', {
                    coursCode,
                    title: messageTitle,
                    content: messageContent,
                    file: null,
                    important: isImportant,
                    author
                });
                console.error('Error:', error);
            });
    });


    // Handle file form submission (with file)
    document.getElementById('formFile').addEventListener('submit', function (e) {
        e.preventDefault();

        const fileTitle = document.getElementById('fileTitle').value;
        const fileDescription = document.getElementById('fileDescription').value;
        const fileInput = document.getElementById('fileUpload');
        const file = fileInput.files[0];
        const author = 1; // Replace with actual logged-in user ID

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
                // Handle success or errors here
                console.log('File created:', data);
                $('#myModal').modal('hide');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
