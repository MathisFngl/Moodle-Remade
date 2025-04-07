<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <main class="compte">
        <h2>Informations du Compte</h2>
        <section class="informations-profil">
            <p><strong>Nom :</strong> Gardiol</p>
            <p><strong>Adresse Email :</strong> Emilie</p>
        </section>

        <section class="modifier-informations">
            <h3>Modifier les Informations</h3>
            <form id="modification-form" method="post">
                <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe">

                <label for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe">

                <label for="nouvelle_adresse_email">Nouvelle adresse email :</label>
                <input type="email" id="nouvelle_adresse_email" name="nouvelle_adresse_email">

                <label for="avatar">Téléverser un avatar :</label>
                <input type="file" id="avatar" name="avatar">

                <button type="submit">Enregistrer les modifications</button>
                <p class="message-erreur" ></p>
            </form>
        </section>
    </main>
</div>

<script src="scripts/profile.js"></script>
</body>
</html>
