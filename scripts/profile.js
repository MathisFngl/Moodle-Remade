document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('modification-form');
    const messageErreur = document.getElementById('message-erreur');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const motDePasse = document.getElementById('nouveau_mot_de_passe').value;
        const confirmationMotDePasse = document.getElementById('confirmer_mot_de_passe').value;
        const email = document.getElementById('nouvelle_adresse_email').value;


        if (motDePasse !== confirmationMotDePasse) {
            messageErreur.textContent = "Les mots de passe ne correspondent pas.";
            return;
        }

        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regexEmail.test(email)) {
            messageErreur.textContent = "Adresse email invalide.";
            return;
        }

    });
});
