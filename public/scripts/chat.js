
const body = document.body;

fetch('/chat-fragment')
    .then(response => response.text())
    .then(html => {
        body.insertAdjacentHTML('beforeend', html);

        const openChatButton = document.getElementById('openChat');
        const chatPanel = document.getElementById('chatPanel');

        if (!openChatButton || !chatPanel) {
            console.error('Missing chat elements!');
            return;
        }

        openChatButton.addEventListener('click', () => {
            if (chatPanel.classList.contains('hidden')) {
                chatPanel.classList.remove('hidden');
                chatPanel.style.display = 'block';
            } else {
                chatPanel.classList.add('hidden');
                chatPanel.style.display = 'none';
            }
        });
    })
    .catch(error => console.error('Erreur lors du chargement du composant chat:', error));