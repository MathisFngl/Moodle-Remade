<?php
    include("header.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/cours_acceuil.css">
    <title>Cours</title>
</head>

<body>
    <div class="conteneur">
        <div class="path_container">
            <ol class="path_list">
                <li class="path_item"> <a href="index.php"> Accueil </a> </li>
                <li class="path_item"> <a href="#fise_info"> FISE Informatique </a> </li>
                <li class="path_item"> <span href="index.php"> WE4E : Langages Web </span> </li>
            </ol>
        </div>
        <div class="title_container">
            <div class="code_uv_container"><h1>WE4E</h1></div>
            <div class="title_uv_container"><h1> Technologies et programmation WEB </h1></div>
        </div>
        <div class="cours_nav">
            <a class="current">Cours</a>
            <a href="cours_participants.php">Participants</a>
            <a href="cours_notes.php">Notes</a>
        </div>

        <!-- Bouton pour ouvrir la modale -->
        <button id="openModalBtn"><img src="images/+.png" alt="Ajouter" class="btn-icon"></button>

        <!-- Div invisible pour contenir les nouveaux éléments -->
        <div id="addedElementsContainer" style="display: none;"></div>

        <!-- Modale avec deux onglets -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un élément</h2>

                <!-- Onglets -->
                <div class="tabs">
                    <button id="tabMessage" class="tab-button active">Message</button>
                    <button id="tabFile" class="tab-button">Fichier</button>
                </div>

                <!-- Contenu du formulaire pour Message -->
                <form id="formMessage" class="tab-content active">
                    <label for="messageType">Type :</label>
                    <select id="messageType" name="messageType" required>
                        <option value="Important">Important</option>
                        <option value="Information">Information</option>
                    </select>
                    <br><br>
                    <label for="messageTitle">Titre :</label>
                    <input type="text" id="messageTitle" name="messageTitle" required>
                    <br><br>
                    <label for="messageContent">Message :</label>
                    <textarea id="messageContent" name="messageContent" rows="4" required></textarea>
                    <br><br>
                    <button type="submit">Ajouter Message</button>
                </form>

                <!-- Contenu du formulaire pour Fichier -->
                <form id="formFile" class="tab-content">
                    <label for="fileTitle">Titre :</label>
                    <input type="text" id="fileTitle" name="fileTitle" required>
                    <br><br>
                    <label for="fileDescription">Description :</label>
                    <textarea id="fileDescription" name="fileDescription" rows="2" required></textarea>
                    <br><br>
                    <label for="fileUpload">Déposer un fichier :</label>
                    <input type="file" id="fileUpload" name="fileUpload" required>
                    <br><br>
                    <button type="submit">Ajouter Fichier</button>
                </form>
            </div>
        </div>

        <script src="scripts/add_file.js"></script>
    </div>
</body>
</html>
