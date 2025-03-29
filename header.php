<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Moodle UTBM</title>
</head>

<div class="navbar">
    <a href="index.php"class="normal_padding">Tableau de bord</a>
    <a href="mes_cours.php"class="normal_padding">Mes cours</a>
    <div class="dropdown">
        <button class="dropbtn">Sites UTBM </button>
        <div class="dropdown-content">
            <a href="https://utbm.sharepoint.com/Sites/Portail/">Mon Espace Etudiant</a>
            <a href="https://outlook.office.com/mail/">Outlook</a>
            <a href="https://bibliotheque.utbm.fr/">Bibliothèque</a>
            <a href="https://ae.utbm.fr/">Association des Etudiants</a>
            <a href="https://www.utbm.fr/">UTBM</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">Semestre à l'UTBM </button>
        <div class="dropdown-content">
            <a href="#annales">Annales</a>
            <a href="#guide_des_ue">Guide des UE</a>
            <a href="#calendriers_plannings">Calendriers / Plannings</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">Stages/S.E.E. </button>
        <div class="dropdown-content">
            <a href="#stages">Stages</a>
            <a href="#see">S.E.E.</a>
            <a href="#entreprises partenaires">Entreprises partenaires</a>
        </div>
    </div>

      <div class="right ">
          <a href="admin.php" class="normal_padding"><i class="fa fa-cubes"></i> Admin </a>
        <a href="#notifications" class="small_padding"><i class="fa fa-bell "></i></a>
        <a id="openChat" class="normal_padding"><i class="fa fa-chat"></i> Messagerie</a>
        <a href="profile.php" class="normal_padding"><i class="fa fa-user"></i> Profil</a>
          <!--- A mettre dans USER ---!>
        <a href="login.php" class="normal_padding"><i class="fa fa-sign-out"></i> Déconnexion</a>
    </div>
</div>
<div id="chatContainer"></div>
<script src="scripts/chat.js"></script>
