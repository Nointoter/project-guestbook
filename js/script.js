document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const usernameInput = document.getElementById("username");
    const emailInput = document.getElementById("email");
    const captchaInput = document.getElementById("captcha");
    const textInput = document.getElementById("text");

    form.addEventListener("submit", function(event) {
        let valid = true;
        
        // Username validation
        const username = usernameInput.value.trim();
        if (!/^[a-zA-Z0-9]+$/.test(username)) {
            alert("Username must contain only letters and numbers.");
            valid = false;
        }
        
        // Email validation
        const email = emailInput.value.trim();
        if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            alert("Please enter a valid email address.");
            valid = false;
        }

        // CAPTCHA validation (optional, if CAPTCHA is implemented)
        const captcha = captchaInput.value.trim();
        if (!/^[a-zA-Z0-9]+$/.test(captcha)) {
            alert("CAPTCHA must contain only letters and numbers.");
            valid = false;
        }

        // Text validation
        const text = textInput.value.trim();
        if (text.length === 0) {
            alert("Text field cannot be empty.");
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});