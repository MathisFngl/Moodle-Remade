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

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container mt-5">
    <ul class="nav nav-tabs justify-content-center" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="ue-tab" data-bs-toggle="tab" data-bs-target="#ue" type="button" role="tab">Gestion des UE</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Gestion des Utilisateurs</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="adminTabsContent">
        <div class="tab-pane fade show active" id="ue" role="tabpanel">
            <div class="text-center p-2 bg-primary text-white">Gestion des UE</div>
            <div class="table-responsive mt-3">
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
            <button class="btn btn-success mt-3">Ajouter une UE</button>
        </div>
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="text-center p-2 bg-primary text-white">Gestion des Utilisateurs</div>
            <div class="table-responsive mt-3">
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
                            <td><img src="images/bombardino.jpg" alt="Profil" class="rounded-circle" width="30" height="30"></td>
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
            <button class="btn btn-success mt-3">Ajouter un Utilisateur</button>
        </div>
    </div>
</div>
</body>
</html>