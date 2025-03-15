function togglePwd() {
    var passwordField = document.getElementById("password");
    var toggleBtn = document.getElementById("togglePassword");

    if (passwordField.type === "password") {
        passwordField.type = "text"; // Show password
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>'; // Eye with slash icon
        console.log("Password is now visible.");
    } else {
        passwordField.type = "password"; // Hide password
        toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Eye icon
        console.log("Password is now hidden.");
    }
}