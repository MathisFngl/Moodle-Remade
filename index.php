<!DOCTYPE html>
<html lang="en">

<?php
    include("header.php");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Moodle</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<div class="dashboard-container">
    <h1>Tableau de bord</h1>

    <div class="sections">
        <div class="section">
            <h2>Cours consultés récemment</h2>
            <ul id="recent-courses">

                <li><a href="#">SY43 - Android Development</a></li>
                <li><a href="cours_cours.php">WE4A - Technologie et programmation WEB</a></li>
                <li><a href="#">IT44 - Analyse Numérique</a></li>
                <li><a href="#">WE4B - Technologie WEB avancées</a>
            </ul>
        </div>

        <div class="section">
            <h2>Cours favoris</h2>
            <p id="favorites">Aucun cours marqué comme favori</p>
        </div>
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <h2>Calendrier - <span id="current-date"></span></h2>
        </div>
        <div id="calendar"></div>
    </div>
</div>
<script src="scripts/index.js"></script>
</body>
</html>

