document.getElementById("saveNotes").addEventListener("click", () => {
    const saveButton = document.getElementById("saveNotes");
    const coursCode = saveButton.getAttribute("data-code");
    const evaluationName = document.getElementById("evaluationName").value.trim();
    const maxGrade = document.getElementById("maxGrade").value.trim();
    const notes = [];

    if (!evaluationName || !maxGrade) {
        alert("Veuillez entrer un nom pour l'évaluation et un barème.");
        return;
    }

    // Collecter les notes des inputs
    document.querySelectorAll(".gradeInput").forEach((input) => {
        const idUtilisateur = input.dataset.userId;
        const note = input.value;

        if (note !== "") {
            notes.push({
                idUtilisateur: idUtilisateur,
                note: parseFloat(note)
            });
        }
    });

    if (notes.length === 0) {
        alert("Aucune note saisie.");
        return;
    }

    // Étape 1 : Créer l'examen
    fetch("/examen/creer", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            nom: evaluationName,
            bareme: maxGrade,
            codeCours: coursCode
        })
    })
        .then(async (response) => {
            const contentType = response.headers.get("content-type");

            if (!response.ok || !contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                throw new Error("Erreur lors de la création de l'examen : " + text);
            }

            return response.json();
        })
        .then((data) => {
            const idExamen = data.idExamen;

            // Étape 2 : Enregistrer les notes
            return fetch(`/cours/${coursCode}/notes/enregistrer`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    idExamen: idExamen,
                    notes: notes
                })
            });
        })
        .then(async (response) => {
            const contentType = response.headers.get("content-type");

            if (!response.ok || !contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                throw new Error("Erreur lors de l'enregistrement des notes : " + text);
            }

            return response.json();
        })
        .then((data) => {
            if (data.success) {
                alert(data.message || "Les notes ont été enregistrées avec succès !");
            } else {
                alert(data.error || "Une erreur est survenue lors de l'enregistrement des notes.");
            }
        })
        .catch((error) => {
            console.error("Erreur :", error);
            alert(error.message || "Erreur réseau ou serveur.");
        });
});
