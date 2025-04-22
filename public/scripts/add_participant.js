document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#searchInput");
    const resultContainer = document.querySelector("#autocomplete-results");

    // Vérifier l'existence des éléments HTML
    if (!input || !resultContainer) {
        console.error("⚠️ Erreur : Les éléments HTML nécessaires n'existent pas !");
        return;
    }

    input.addEventListener("input", function () {
        let query = input.value.trim();
        console.log("🔍 Requête envoyée :", query); // Vérification de la saisie utilisateur

        if (query.length > 0) {
            fetch(`/search_students?q=${query}`)
                .then(response => {
                    console.log("📡 Réponse HTTP reçue :", response.status);
                    if (!response.ok) {
                        throw new Error("Erreur HTTP " + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("✅ Données reçues :", data); // Vérification de la réponse Symfony
                    resultContainer.innerHTML = "";

                    if (data.length === 0) {
                        resultContainer.innerHTML = "<div class='autocomplete-item'>Aucun résultat trouvé</div>";
                        console.warn("⚠️ Aucun utilisateur trouvé !");
                        return;
                    }

                    data.forEach(utilisateur => {
                        console.log("👤 Utilisateur trouvé :", utilisateur.name);
                        let div = document.createElement("div");
                        div.textContent = utilisateur.name;
                        div.className = "autocomplete-item";

                        div.addEventListener("click", () => {
                            console.log("✍️ Sélection :", utilisateur.name);
                            input.value = utilisateur.name;
                            resultContainer.innerHTML = "";
                        });

                        resultContainer.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error("❌ Erreur AJAX :", error);
                    resultContainer.innerHTML = "<div class='autocomplete-item error'>Erreur de chargement</div>";
                });
        } else {
            console.log("🛑 Champ vide, pas de requête envoyée.");
            resultContainer.innerHTML = "";
        }
    });

    document.addEventListener("click", function (event) {
        if (!input.contains(event.target) && !resultContainer.contains(event.target)) {
            console.log("🧹 Nettoyage des résultats d'autocomplétion.");
            resultContainer.innerHTML = "";
        }
    });
});
