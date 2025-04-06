<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Pédagogique</title>
    <link rel="stylesheet" href="styles/accueil.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">UTBM</div>
        <nav>
            <ul class="nav-links">
                <li><a href="https://www.utbm.fr/">UTBM</a></li>
                <li><a href="https://outlook.office.com/mail/">Outlook</a></li>
                <li><a href="https://bibliotheque.utbm.fr/">Bibliothèque</a></li>
                <li><a href="https://ae.utbm.fr/">Association des Étudiants</a></li>
            </ul>
        </nav>
        <div class="login-button">
            <a href="login.php" class="btn-login">Se connecter</a>
        </div>
        <div class="menu-toggle" id="menu-toggle">&#9776;</div>
    </div>
</header>

<main>
    <!-- Bandeau d'accueil -->
    <section class="banner">
        <div class="container">
            <h1>Plateforme Pédagogique</h1>
            <p>Bienvenue sur la plateforme pédagogique de l’UTBM</p>
        </div>
    </section>

    <!-- Section des annonces -->
    <section class="announcements">
        <div class="container">
            <h2>Annonces</h2>
            <div class="announcements-grid">
                <div class="announcement">
                    <img src="images/actu.jpg" alt="1">
                    <h3>Actualité 1</h3>
                    <a href="#">En savoir plus</a>
                </div>
                <div class="announcement">
                    <img src="images/actu.jpg" alt="2">
                    <h3>Actualité 2</h3>
                    <a href="#">En savoir plus</a>
                </div>
                <div class="announcement">
                    <img src="images/actu.jpg" alt="3">
                    <h3>Actualité 3</h3>
                    <a href="#">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="scripts/accueil.js"></script>
</body>
</html>
