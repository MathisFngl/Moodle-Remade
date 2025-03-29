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
            <a href="cours_cours.php">Cours</a>
            <a href="cours_participants.php">Participants</a>
            <a class="current">Notes</a>
        </div>
        <span class="add">
            <a href="ajouter_note.php">add mark</a>
        </span>

    <script src="scripts/script.js"></script>
</body>