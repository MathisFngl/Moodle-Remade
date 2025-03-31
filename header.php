<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Moodle UTBM</title>
</head>
<body>
<nav class="navbar">
    <div class="nav-left">
        <a class="normal_padding" href="index.php">Tableau de bord</a>
        <a class="normal_padding" href="mes_cours.php">Mes cours</a>

        <div class="dropdown">
            <button class="dropbtn normal_padding">Sites UTBM <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
                <a href="https://www.utbm.fr/">UTBM</a>
                <a href="https://outlook.office.com/mail/">Outlook</a>
                <a href="https://bibliotheque.utbm.fr/">Bibliothèque</a>
                <a href="https://ae.utbm.fr/">Association des Étudiants</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn normal_padding">Semestre à l'UTBM <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
                <a href="#annales">Annales</a>
                <a href="#guide_des_ue">Guide des UE</a>
                <a href="#calendriers_plannings">Calendriers / Plannings</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn normal_padding">Stages/S.E.E. <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
                <a href="#stages">Stages</a>
                <a href="#see">S.E.E.</a>
                <a href="#entreprises_partenaires">Entreprises partenaires</a>
            </div>
        </div>
    </div>

    <div class="right">
        <a class="normal_padding" href="admin.php"><i class="fa fa-cubes"></i> Admin</a>
        <a class="normal_padding" href="#notifications"><i class="fa fa-bell"></i></a>
        <a class="normal_padding" id="openChat"><i class="fa fa-comments"></i> Messagerie</a>
        <a class="normal_padding" href="profile.php"><i class="fa fa-user"></i> Profil</a>
        <a class="normal_padding" href="login.php"><i class="fa fa-sign-out"></i> Déconnexion</a>
    </div>
</nav>

<div id="chatContainer"></div>
<script src="scripts/chat.js"></script>
</body>
</html>
