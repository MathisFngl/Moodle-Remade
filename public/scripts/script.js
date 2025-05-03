function togglePwd() {
    var passwordField = document.getElementById("inputPassword");
    var toggleBtn = document.getElementById("togglePassword");

    if (passwordField.type === "password") {
        passwordField.type = "text"; // mot de passe visible
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        console.log("Password is now visible.");
    } else {
        passwordField.type = "password"; // invisible
        toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        console.log("Password is now hidden.");
    }
}