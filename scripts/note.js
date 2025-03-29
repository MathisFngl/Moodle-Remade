const saveButton = document.getElementById('saveNotes');

saveButton.addEventListener('click', function() {
    const evaluationName = document.getElementById('evaluationName').value;
    const maxGrade = document.getElementById('maxGrade').value;

    if (!evaluationName || !maxGrade) {
        alert("Veuillez entrer un nom pour l'évaluation et un barème.");
        return;
    }

    console.log(`Les notes pour l'évaluation "${evaluationName}" (sur ${maxGrade}) ont été enregistrées.`);
});
