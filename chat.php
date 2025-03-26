<link rel="stylesheet" href="styles/chat.css">

<div id="chatPanel" class="chat-panel hidden">
    <div class="header">
        <span>Messagerie</span>
        <button id="newDiscussion" class="new-discussion">+ Nouvelle Discussion</button>
    </div>
    <div class="content">
        <!-- Liste des discussions -->
        <div class="chat-discussion">
            <div class="chat-message">
                <p class="sender-name">Alice :</p>
                <p class="message">Bonjour, comment ça va ?</p>
            </div>
            <div class="chat-message">
                <p class="sender-name">Vous :</p>
                <p class="message">Ça va bien, merci ! Et toi ?</p>
            </div>
            <div class="chat-message">
                <p class="sender-name">Alice :</p>
                <p class="message">Je vais bien, merci de demander ! 😊</p>
            </div>
        </div>
    </div>
    <div class="footer">
        <input type="text" id="chatInput" placeholder="Écrire un message..." />
        <button id="sendMessage">Envoyer</button>
        
    </div>
</div>
