
document.addEventListener("DOMContentLoaded", function () {

  const form = document.getElementById("registerForm");
  const passwordInput = document.getElementById("password");
  const passwordError = document.getElementById("passwordError");
  const togglePassword = document.getElementById("togglePassword");

  if (!form || !passwordInput || !passwordError || !togglePassword) {
    console.log("Required elements not found");
    return;
  }

  // 👁 SHOW / HIDE PASSWORD
  togglePassword.addEventListener("click", function () {
    const isHidden = passwordInput.type === "password";
    passwordInput.type = isHidden ? "text" : "password";
    this.textContent = isHidden ? "🙈" : "👁";
  });

  // 🔴 REAL-TIME PASSWORD VALIDATION
  passwordInput.addEventListener("input", function () {
    if (passwordInput.value.length < 6) {
      passwordError.textContent = "Password must be at least 6 characters";
    } else {
      passwordError.textContent = "";
    }
  });

  // 🔒 FINAL CHECK BEFORE SUBMIT
  form.addEventListener("submit", function (e) {
    if (passwordInput.value.length < 6) {
      e.preventDefault();
      passwordError.textContent = "Password must be at least 6 characters";
    }
  });

});



