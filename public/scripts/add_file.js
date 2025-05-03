document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("myModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementsByClassName("close")[0];
    const addedElementsContainer = document.getElementById("addedElementsContainer");

    // Onglets
    const tabMessage = document.getElementById("tabMessage");
    const tabFile = document.getElementById("tabFile");
    const formMessage = document.getElementById("formMessage");
    const formFile = document.getElementById("formFile");

    // Gérer le basculement entre les onglets
    tabMessage.onclick = function () {
        tabMessage.classList.add("active");
        tabFile.classList.remove("active");
        formMessage.classList.add("active");
        formFile.classList.remove("active");
    };

    tabFile.onclick = function () {
        tabFile.classList.add("active");
        tabMessage.classList.remove("active");
        formFile.classList.add("active");
        formMessage.classList.remove("active");
    };

    // Ouvrir la modale
    openModalBtn.onclick = function () {
        modal.style.display = "block";
    };

    // Fermer la modale
    closeBtn.onclick = function () {
        modal.style.display = "none";
    };

    // Fermer la modale si on clique en dehors
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    // Fonction pour obtenir la date et l'heure actuelles au format lisible
    function getCurrentDateTime() {
        const now = new Date();
        const options = {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        };
        return now.toLocaleDateString("fr-FR", options);
    }

    // Ajouter un message
    formMessage.onsubmit = function (event) {
        event.preventDefault();
        const messageType = document.getElementById("messageType").value;
        const messageTitle = document.getElementById("messageTitle").value;
        const messageContent = document.getElementById("messageContent").value;

        if (messageType && messageTitle && messageContent) {
            const newElement = document.createElement("div");
            newElement.classList.add("element");

            const icon = document.createElement("img");
            icon.src =
                messageType === "Important"
                    ? "images/Important.png"
                    : "images/Information.png";
            icon.alt = messageType === "Important" ? "Important Icon" : "Information Icon";
            icon.classList.add("icon");

            const title = document.createElement("span");
            title.textContent = messageTitle;

            const date = document.createElement("div");
            date.classList.add("date");
            date.textContent = getCurrentDateTime();

            newElement.onclick = function () {
                alert(`Type: ${messageType}\nTitre: ${messageTitle}\nMessage: ${messageContent}`);
            };

            newElement.appendChild(icon);
            newElement.appendChild(title);
            newElement.appendChild(date);
            addedElementsContainer.insertBefore(newElement, addedElementsContainer.firstChild);

            addedElementsContainer.style.display = "block";
            modal.style.display = "none";
            formMessage.reset();
        }
    };

    // Ajouter un fichier
    formFile.onsubmit = function (event) {
        event.preventDefault();
        const fileTitle = document.getElementById("fileTitle").value;
        const fileDescription = document.getElementById("fileDescription").value;
        const fileUpload = document.getElementById("fileUpload").files[0];

        if (fileTitle && fileDescription && fileUpload) {
            const newElement = document.createElement("a");
            newElement.classList.add("element");
            newElement.href = URL.createObjectURL(fileUpload);
            newElement.download = fileUpload.name;

            const icon = document.createElement("img");
            icon.src = "images/pdf.png";
            icon.alt = "File Icon";
            icon.classList.add("icon");

            const titleAndDescription = document.createElement("span");
            titleAndDescription.textContent = `${fileTitle} - ${fileDescription}`;

            const date = document.createElement("div");
            date.classList.add("date");
            date.textContent = getCurrentDateTime();

            newElement.appendChild(icon);
            newElement.appendChild(titleAndDescription);
            newElement.appendChild(date);
            addedElementsContainer.insertBefore(newElement, addedElementsContainer.firstChild);

            addedElementsContainer.style.display = "block";
            modal.style.display = "none";
            formFile.reset();
        }
    };
});
