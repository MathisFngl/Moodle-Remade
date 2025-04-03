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

        <!-- Ajouter un bouton pour ouvrir la modale -->
        <button id="openModalBtn"><img src="images/+.png" alt="Ajouter" class="btn-icon"></button>
    
        <!-- Modale -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ajouter un Cours/TD/TP</h2>
                <form id="addElementForm">
                    <label for="type">Type :</label>
                    <select id="type" name="type">
                        <option value="CM">Cours</option>
                        <option value="TD">TD</option>
                        <option value="TP">TP</option>
                    </select>
                    <br><br>

                    <label for="number">Numéro :</label>
                    <input type="number" id="number" name="number" required min="1">
                    <br><br>

                    <button type="submit">+</button>
                </form>
            </div>
        </div>

        <script src="scripts/add_file.js"></script>

        <div class="class_part">
            <a class="title">Cours</a>
            <div class="class_content">
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> CM 1
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> CM 2
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> CM 3
                </div>
            </div>
        </div>

        <div class="class_part">
            <a class="title">TD</a>
            <div class="class_content">
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TD 1
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TD 2
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TD 3
                </div>
            </div>
        </div>

        <div class="class_part">
            <a class="title">TP</a>
            <div class="class_content">
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TP 1
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TP 2
                </div>
                <div class="element">
                    <img src="images/pdf.png" alt="Icon" class="icon"> TP 3
                </div>
            </div>
        </div>

    </div>

    <script src="scripts/add_file.js"></script>
</body>
</html>
