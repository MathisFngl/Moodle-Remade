document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#searchInput");
    const resultContainer = document.createElement("div");
    resultContainer.className = "autocomplete-results";
    input.parentNode.appendChild(resultContainer);

    input.addEventListener("input", function () {
        let query = input.value.trim();
        if (query.length > 0) {
            fetch(`/search_students?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    resultContainer.innerHTML = "";
                    data.forEach(student => {
                        let div = document.createElement("div");
                        div.textContent = student.name;
                        div.className = "autocomplete-item";
                        div.addEventListener("click", () => {
                            input.value = student.name;
                            resultContainer.innerHTML = "";
                        });
                        resultContainer.appendChild(div);
                    });
                })
                .catch(error => console.error("Erreur de récupération:", error));
        } else {
            resultContainer.innerHTML = "";
        }
    });
});
