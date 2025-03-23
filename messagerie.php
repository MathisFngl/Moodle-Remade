<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include 'header.php'; ?> 

    <div class="container">
        <main class="messagerie">
            <section class="conversations-recentes">
                <h2>Conversations Récentes</h2>
                <ul>
                    <li><a href="#">Utilisateur 1</a></li>
                    <li><a href="#">Utilisateur 2</a></li>
                </ul>
            </section>

            <section class="interface-conversation">
                <h3>Conversation avec <span id="nom-destinataire">Utilisateur Sélectionné</span></h3>
                <div class="messages">
                    <div class="message entrant">Bonjour !</div>
                    <div class="message sortant">Salut ! Comment ça va ?</div>
                </div>
                <form action="#" method="post" class="nouveau-message">
                    <textarea placeholder="Écrire un message..."></textarea>
                    <button type="submit">Envoyer</button>
                </form>
            </section>

            <section class="liste-contacts">
                <h3>Contacts</h3>
                <ul>
                    <li><a href="#">Contact A</a></li>
                    <li><a href="#">Contact B</a></li>
                </ul>
            </section>
        </main>
    </div>

    <script src="script.js"></script> 
</body>
</html>