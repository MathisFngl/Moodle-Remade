document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("myModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementsByClassName("close")[0];
    const addElementForm = document.getElementById("addElementForm");
    
    // Récupérer les conteneurs des différentes catégories
    const coursContainer = document.querySelector(".class_part .class_content"); // Pour les cours
    const tdContainer = document.querySelectorAll(".class_part .class_content")[1]; // Pour les TD
    const tpContainer = document.querySelectorAll(".class_part .class_content")[2]; // Pour les TP

    // Ouvrir la modale lorsque le bouton est cliqué
    openModalBtn.onclick = function() {
        modal.style.display = "block";
    };

    // Fermer la modale lorsque la croix est cliquée
    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    // Fermer la modale si l'utilisateur clique en dehors de la fenêtre
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Ajouter un élément lorsqu'on soumet le formulaire
    addElementForm.onsubmit = function(event) {
        event.preventDefault();

        // Récupérer les valeurs du formulaire
        const type = document.getElementById("type").value;
        const number = document.getElementById("number").value;

        if (type && number) {
            // Créer un nouvel élément
            const newElement = document.createElement("div");
            newElement.classList.add("element");

            // Créer un élément image pour l'icône
            const icon = document.createElement("img");
            icon.src = "images/pdf.png";  // L'icône que tu veux afficher
            icon.alt = "Icon";
            icon.classList.add("icon");

            // Ajouter l'icône et le texte à l'élément
            newElement.appendChild(icon);
            newElement.appendChild(document.createTextNode(`${type} ${number}`));

            // Choisir le conteneur approprié en fonction du type
            let targetContainer;
            if (type === "CM") {
                targetContainer = coursContainer;
            } else if (type === "TD") {
                targetContainer = tdContainer;
            } else if (type === "TP") {
                targetContainer = tpContainer;
            }

            // Ajouter le nouvel élément au conteneur cible
            if (targetContainer) {
                targetContainer.appendChild(newElement);
            }

            // Fermer la modale
            modal.style.display = "none";

            // Réinitialiser le formulaire
            addElementForm.reset();
        } else {
            alert("Veuillez remplir tous les champs.");
        }
    };
});
