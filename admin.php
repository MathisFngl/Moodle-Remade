<?php
include("header.php");

$ues = [
    ["code" => "INF1", "nom" => "Informatique 1", "desc" => "Introduction à la programmation.", "image" => "path_to_image_1.jpg"],
    ["code" => "MAT2", "nom" => "Mathématiques 2", "desc" => "Algèbre et analyse avancées.", "image" => "path_to_image_2.jpg"],
    ["code" => "PHY3", "nom" => "Physique 3", "desc" => "Mécanique et thermodynamique.", "image" => "path_to_image_3.jpg"],
    ["code" => "CHM4", "nom" => "Chimie 4", "desc" => "Chimie organique et inorganique.", "image" => "path_to_image_4.jpg"],
    ["code" => "ELE5", "nom" => "Électronique 5", "desc" => "Circuits et systèmes électroniques.", "image" => "path_to_image_5.jpg"]
];

$users = [
    ["prenom" => "Alice", "nom" => "Dupont", "email" => "alice.dupont@utbm.fr", "role" => "étudiant", "admin" => false],
    ["prenom" => "Bob", "nom" => "Martin", "email" => "bob.martin@utbm.fr", "role" => "prof", "admin" => true],
    ["prenom" => "Charlie", "nom" => "Lemoine", "email" => "charlie.lemoine@utbm.fr", "role" => "admin", "admin" => true],
    ["prenom" => "David", "nom" => "Bernard", "email" => "david.bernard@utbm.fr", "role" => "prof", "admin" => false],
    ["prenom" => "Emma", "nom" => "Rousseau", "email" => "emma.rousseau@utbm.fr", "role" => "étudiant", "admin" => false]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/admin.js"></script>
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
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ues as $ue): ?>
                        <tr>
                            <td><?= htmlspecialchars($ue["code"]); ?></td>
                            <td><?= htmlspecialchars($ue["nom"]); ?></td>
                            <td><?= htmlspecialchars($ue["desc"]); ?></td>
                            <td>
                                <?php if (!empty($ue["image"])): ?>
                                    <img src="<?= $ue['image']; ?>" alt="<?= $ue['nom']; ?>" class="img-thumbnail" style="width: 50px;">
                                <?php else: ?>
                                    <span>Pas d'image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editUE(<?= json_encode($ue) ?>)'>Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-success mt-3" onclick="editUE(null)">Ajouter une UE</button>
        </div>
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="text-center p-2 bg-primary text-white">Gestion des Utilisateurs</div>
            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Admin</th>
                        <th>UEs</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user["nom"]); ?></td>
                            <td><?= htmlspecialchars($user["prenom"]); ?></td>
                            <td><?= htmlspecialchars($user["email"]); ?></td>
                            <td><?= htmlspecialchars($user["role"]); ?></td>
                            <td><?= $user["admin"] ? 'Oui' : 'Non'; ?></td>
                            <td>
                                <?php // Display assigned UEs as badges
                                foreach ($ues as $ue) {
                                    if (rand(0, 1)) {
                                        echo '<span class="badge bg-secondary me-2">'.$ue["code"].'</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editUser(<?= json_encode($user) ?>)'>Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-success mt-3" onclick="editUser(null)">Ajouter un Utilisateur</button>
        </div>
    </div>
</div>

<!-- MODALE UE -->
<div class="modal fade" id="ueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="ueForm" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Créer / Modifier une UE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" class="form-control" id="ueCode" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" id="ueNom" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="ueDesc" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" id="ueImage">
                    <small class="text-muted">Choisissez une image pour cette UE</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- MODALE UTILISATEUR -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="userForm">
            <div class="modal-header">
                <h5 class="modal-title">Créer / Modifier un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="w-45">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" required>
                    </div>
                    <div class="w-45">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="w-45">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="w-45">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" placeholder="par défaut : 1234">
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="w-45">
                        <label class="form-label">Rôle</label>
                        <select class="form-select" id="role">
                            <option value="étudiant">Étudiant</option>
                            <option value="prof">Professeur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div class="w-45 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="isAdmin">
                        <label class="form-check-label ms-2">Administrateur</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">UEs assignées</label>
                    <div id="ueBadges" class="d-flex flex-wrap">
                    </div>
                    <select class="form-select mt-2" id="ueSelect">
                        <option value="">Ajouter une UE</option>
                        <?php foreach ($ues as $ue): ?>
                            <option value="<?= $ue['code'] ?>"><?= $ue['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
