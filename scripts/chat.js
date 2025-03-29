
const body = document.body;

fetch('chat.php')
    .then(response => response.text())
    .then(html => {
        body.insertAdjacentHTML('beforeend', html);
        const openChatButton = document.getElementById('openChat');
        const chatPanel = document.getElementById('chatPanel');

        // Interactivité pour le bouton
        openChatButton.addEventListener('click', () => {
            if (chatPanel.classList.contains('hidden')) {
                chatPanel.classList.remove('hidden');
                chatPanel.style.display = 'flex';
            } else {
                chatPanel.classList.add('hidden');
                chatPanel.style.display = 'none';
            }
        });
    })
    .catch(error => console.error('Erreur lors du chargement du fichier PHP:', error));
