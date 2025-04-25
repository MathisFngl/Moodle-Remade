document.addEventListener("DOMContentLoaded", function () {
    console.log("🚀 Script `add_participant.js` chargé !");

    const input = document.querySelector("#searchInput");
    const resultContainer = document.querySelector("#autocomplete-results");
    const form = document.querySelector("#participantForm");
    const hiddenInput = document.querySelector("#id_utilisateur");
    const hiddenCodeCoursInput = document.querySelector("#code_cours");
    const participantList = document.querySelector("#participantList");

    if (!input || !resultContainer || !form || !hiddenInput || !hiddenCodeCoursInput || !participantList) {
        console.error("❌ Erreur : Certains éléments du DOM sont introuvables.");
        return;
    }

    input.addEventListener("input", function () {
        let query = input.value.trim();
        console.log("🔍 Requête envoyée :", query);

        if (query.length > 0) {
            fetch(`/search_students?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    console.log("✅ Données reçues :", data);
                    resultContainer.innerHTML = "";

                    if (data.length === 0) {
                        resultContainer.innerHTML = "<div class='autocomplete-item'>Aucun résultat trouvé</div>";
                        return;
                    }

                    data.forEach(utilisateur => {
                        console.log("👤 Utilisateur trouvé :", utilisateur.name);

                        let div = document.createElement("div");
                        div.textContent = utilisateur.name;
                        div.className = "autocomplete-item";
                        div.addEventListener("click", () => {
                            input.value = utilisateur.name;
                            hiddenInput.value = utilisateur.id;
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
            resultContainer.innerHTML = "";
        }
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const utilisateurId = hiddenInput.value;
        const coursCode = hiddenCodeCoursInput.value.trim();

        if (!utilisateurId || !coursCode) {
            alert("⚠️ Veuillez sélectionner un utilisateur et un cours avant d'ajouter !");
            return;
        }

        console.log("📤 Données envoyées :", { id_utilisateur: utilisateurId, code_cours: coursCode });

        fetch(`/cours/${coursCode}/ajouter-participant`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_utilisateur: utilisateurId, code_cours: coursCode })
        })
            .then(response => {
                console.log("🔍 Statut HTTP :", response.status);
                return response.json();
            })
            .then(data => {
                console.log("✅ Réponse serveur :", data);

                if (data.error) {
                    alert(`⚠️ Erreur : ${data.error}`);
                    return;
                }

                alert("🎉 Utilisateur ajouté avec succès !");
                location.reload();
            })
            .catch(error => {
                console.error("❌ Erreur lors de l'ajout :", error);
                alert("⚠️ Erreur lors de l'ajout du participant.");
            });
    });
});