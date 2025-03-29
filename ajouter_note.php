<?php
include("header.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/cours_acceuil.css">
    <link rel="stylesheet" href="styles/note.css">
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
        <a href="cours_cours.php">Cours</a>
        <a href="cours_participants.php">Participants</a>
        <a class="current">Notes</a>
    </div>

    <h1>Saisir les Notes</h1>
    <div>
        <label for="evaluationName">Nom de l'évaluation :</label>
        <input type="text" id="evaluationName" placeholder="Entrez le nom de l'évaluation">
    </div>
    <div>
        <label for="maxGrade">Barème :</label>
        <input type="number" id="maxGrade" placeholder="Entrez le barème" min="1">
    </div>

    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody id="studentTable">
        <tr>
            <td>GOMEZ</td>
            <td>Esteban</td>
            <td><input type="number" class="gradeInput" min="0"></td>
        </tr>
        <tr>
            <td>GOMEZ</td>
            <td>Esteban</td>
            <td><input type="number" class="gradeInput" min="0"></td>
        </tr>
        <tr>
            <td>GOMEZ</td>
            <td>Esteban</td>
            <td><input type="number" class="gradeInput" min="0"></td>
        </tr>
        </tbody>
    </table>

    <button id="saveNotes">Enregistrer les Notes</button>


    <script src="note.js"></script>
</body>
</html>
