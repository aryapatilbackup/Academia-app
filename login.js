document.addEventListener("DOMContentLoaded", function () {

  const form = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");

  // SAFETY CHECK
  if (!form || !emailInput || !passwordInput || !togglePassword) {
    console.log("Login JS: required elements not found");
    return;
  }

  // 👁 SHOW / HIDE PASSWORD
  togglePassword.addEventListener("click", function () {
    const hidden = passwordInput.type === "password";
    passwordInput.type = hidden ? "text" : "password";
    this.textContent = hidden ? "🙈" : "👁";
  });

  // 🔐 FORM SUBMIT
  form.addEventListener("submit", function (e) {

    clearErrors();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    let valid = true;

    if (email === "") {
      showError("emailError", "Email is required");
      valid = false;
    }

    if (password === "") {
      showError("passwordError", "Password is required");
      valid = false;
    }

    // ❗ STOP SUBMIT ONLY IF INVALID
    if (!valid) {
      e.preventDefault();
      return;
    }

    // ✅ VALID → allow submit to login.php
  });

});

function showError(id, message) {
  const el = document.getElementById(id);
  if (el) el.textContent = message;
}

function clearErrors() {
  document.querySelectorAll(".error").forEach(e => e.textContent = "");
}

