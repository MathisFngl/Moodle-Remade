<?php
    include("header.php");

    $ues = [
        ["code" => "INF1", "nom" => "Informatique 1", "desc" => "Introduction à la programmation."],
        ["code" => "MAT2", "nom" => "Mathématiques 2", "desc" => "Algèbre et analyse avancées."],
        ["code" => "PHY3", "nom" => "Physique 3", "desc" => "Mécanique et thermodynamique."],
        ["code" => "CHM4", "nom" => "Chimie 4", "desc" => "Chimie organique et inorganique."],
        ["code" => "ELE5", "nom" => "Électronique 5", "desc" => "Circuits et systèmes électroniques."]
    ];

$users = [
    ["prenom" => "Alice", "nom" => "Dupont", "email" => "alice.dupont@utbm.fr"],
    ["prenom" => "Bob", "nom" => "Martin", "email" => "bob.martin@utbm.fr"],
    ["prenom" => "Charlie", "nom" => "Lemoine", "email" => "charlie.lemoine@utbm.fr"],
    ["prenom" => "David", "nom" => "Bernard", "email" => "david.bernard@utbm.fr"],
    ["prenom" => "Emma", "nom" => "Rousseau", "email" => "emma.rousseau@utbm.fr"]
];
?>

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/admin.css">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <title> Admin </title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
    </head>
    <body>
        <div class="body-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="col">
                        <div class="col-md-12 titre_panel_gestion text-center p-2 bg-primary text-white">
                            Gestion des UE
                        </div>
                        <div class="col-md-12 list-container mt-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Code UE</th>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($ues as $ue): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ue["code"]); ?></td>
                                            <td><?php echo htmlspecialchars($ue["nom"]); ?></td>
                                            <td><?php echo htmlspecialchars($ue["desc"]); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm">Modifier</button>
                                                <button class="btn btn-danger btn-sm">Supprimer</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3 button-container">
                            <button class="btn btn-success button-add">Ajouter une UE</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col">
                        <div class="col-md-12 titre_panel_gestion text-center p-2 bg-primary text-white">
                            Gestion des Utilisateurs
                        </div>
                        <div class="col-md-12 list-container mt-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Photo</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <img src="images/bombardino.jpg" alt="Profil" class="rounded-circle" width="30" height="30">
                                            </td>
                                            <td><?php echo htmlspecialchars($user["nom"]); ?></td>
                                            <td><?php echo htmlspecialchars($user["prenom"]); ?></td>
                                            <td><?php echo htmlspecialchars($user["email"]); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm">Modifier</button>
                                                <button class="btn btn-danger btn-sm">Supprimer</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3 button-container">
                            <button class="btn btn-success button-add">Ajouter un Utilisateur</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>