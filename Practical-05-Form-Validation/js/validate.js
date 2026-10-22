// Practical 5: Client-side Form Validation
// StudentHub Portal - Beginner Friendly JavaScript

// Global variable to store current CAPTCHA code
var currentCaptcha = "";

// 1. Generate Random CAPTCHA
function generateCaptcha() {
    var chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789"; // simple characters (avoid confusing 0/O, 1/I)
    var captcha = "";
    for (var i = 0; i < 5; i++) {
        var randomIndex = Math.floor(Math.random() * chars.length);
        captcha += chars.charAt(randomIndex);
    }
    currentCaptcha = captcha;

    // Draw on canvas
    var canvas = document.getElementById("captcha-canvas");
    var ctx = canvas.getContext("2d");
    
    // Clear canvas background
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#e9ecef";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Draw some random noise lines
    ctx.strokeStyle = "#bbb";
    for (var j = 0; j < 3; j++) {
        ctx.beginPath();
        ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
        ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
        ctx.stroke();
    }

    // Draw CAPTCHA text
    ctx.font = "bold 20px Arial";
    ctx.fillStyle = "#003366";
    ctx.textBaseline = "middle";
    ctx.fillText(captcha, 20, canvas.height / 2);
}

// 2. Helper to set Error message and class
function showError(inputId, errorId, message) {
    var inputElem = document.getElementById(inputId);
    var errorElem = document.getElementById(errorId);
    
    inputElem.classList.remove("valid");
    inputElem.classList.add("invalid");
    
    errorElem.innerText = message;
    errorElem.classList.add("visible");
}

// 3. Helper to set Success (Valid) state
function showSuccess(inputId, errorId) {
    var inputElem = document.getElementById(inputId);
    var errorElem = document.getElementById(errorId);
    
    inputElem.classList.remove("invalid");
    inputElem.classList.add("valid");
    
    errorElem.innerText = "";
    errorElem.classList.remove("visible");
}

// 4. Validate Full Name
function validateFullName() {
    var name = document.getElementById("fullName").value.trim();
    // Rule: letters and spaces only, 3 to 50 characters
    var namePattern = /^[A-Za-z\s]{3,50}$/;

    if (name === "") {
        showError("fullName", "nameError", "Full Name is required.");
        return false;
    } else if (!namePattern.test(name)) {
        showError("fullName", "nameError", "Name must contain only letters (min 3 characters).");
        return false;
    } else {
        showSuccess("fullName", "nameError");
        return true;
    }
}

// 5. Validate Email Address
function validateEmail() {
    var email = document.getElementById("email").value.trim();
    // Standard basic email regex pattern
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (email === "") {
        showError("email", "emailError", "Email address is required.");
        return false;
    } else if (!emailPattern.test(email)) {
        showError("email", "emailError", "Please enter a valid email address (e.g., student@charusat.edu.in).");
        return false;
    } else {
        showSuccess("email", "emailError");
        return true;
    }
}

// 6. Validate Mobile Number (10 digits starting with 6, 7, 8, or 9)
function validateMobile() {
    var mobile = document.getElementById("mobile").value.trim();
    var mobilePattern = /^[6-9][0-9]{9}$/;

    if (mobile === "") {
        showError("mobile", "mobileError", "Mobile number is required.");
        return false;
    } else if (!mobilePattern.test(mobile)) {
        showError("mobile", "mobileError", "Enter a valid 10-digit Indian mobile number starting with 6-9.");
        return false;
    } else {
        showSuccess("mobile", "mobileError");
        return true;
    }
}

// 7. Validate Department Selection
function validateDepartment() {
    var dept = document.getElementById("department").value;

    if (dept === "") {
        showError("department", "deptError", "Please select your department.");
        return false;
    } else {
        showSuccess("department", "deptError");
        return true;
    }
}

// 8. Validate Password and Check Strength
function validatePassword() {
    var password = document.getElementById("password").value;
    var strengthBar = document.getElementById("strengthBar");
    var strengthText = document.getElementById("strengthText");

    if (password === "") {
        showError("password", "passError", "Password is required.");
        strengthBar.style.width = "0%";
        strengthText.innerText = "";
        return false;
    }

    // Calculate strength score
    var score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    // Update Strength Meter UI
    if (score <= 1) {
        strengthBar.style.width = "25%";
        strengthBar.style.backgroundColor = "#dc3545"; // Red
        strengthText.innerText = "Strength: Weak";
        strengthText.style.color = "#dc3545";
    } else if (score === 2 || score === 3) {
        strengthBar.style.width = "65%";
        strengthBar.style.backgroundColor = "#ffc107"; // Yellow
        strengthText.innerText = "Strength: Medium";
        strengthText.style.color = "#d39e00";
    } else {
        strengthBar.style.width = "100%";
        strengthBar.style.backgroundColor = "#28a745"; // Green
        strengthText.innerText = "Strength: Strong";
        strengthText.style.color = "#28a745";
    }

    // Minimum requirement: 8 characters, at least 1 number, 1 special character
    if (password.length < 8) {
        showError("password", "passError", "Password must be at least 8 characters long.");
        return false;
    } else {
        showSuccess("password", "passError");
        return true;
    }
}

// 9. Validate Confirm Password Match
function validateConfirmPassword() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (confirmPassword === "") {
        showError("confirmPassword", "confirmPassError", "Please re-type your password.");
        return false;
    } else if (password !== confirmPassword) {
        showError("confirmPassword", "confirmPassError", "Passwords do not match.");
        return false;
    } else {
        showSuccess("confirmPassword", "confirmPassError");
        return true;
    }
}

// 10. Validate CAPTCHA
function validateCaptchaInput() {
    var userCaptcha = document.getElementById("captchaInput").value.trim().toUpperCase();

    if (userCaptcha === "") {
        showError("captchaInput", "captchaError", "Please enter the CAPTCHA code shown.");
        return false;
    } else if (userCaptcha !== currentCaptcha) {
        showError("captchaInput", "captchaError", "CAPTCHA code is incorrect.");
        return false;
    } else {
        showSuccess("captchaInput", "captchaError");
        return true;
    }
}

// Setup Event Listeners when page loads
window.addEventListener("DOMContentLoaded", function() {
    // Generate initial CAPTCHA
    generateCaptcha();

    // Refresh CAPTCHA button
    document.getElementById("btnRefreshCaptcha").addEventListener("click", function() {
        generateCaptcha();
        document.getElementById("captchaInput").value = "";
    });

    // Real-time blur and input listeners
    document.getElementById("fullName").addEventListener("blur", validateFullName);
    document.getElementById("email").addEventListener("blur", validateEmail);
    document.getElementById("mobile").addEventListener("blur", validateMobile);
    document.getElementById("department").addEventListener("change", validateDepartment);
    document.getElementById("password").addEventListener("input", validatePassword);
    document.getElementById("confirmPassword").addEventListener("keyup", validateConfirmPassword);
    document.getElementById("captchaInput").addEventListener("blur", validateCaptchaInput);

    // Form Submission
    var form = document.getElementById("registrationForm");
    form.addEventListener("submit", function(event) {
        // Prevent default submission
        event.preventDefault();

        // Run all validations
        var isNameValid = validateFullName();
        var isEmailValid = validateEmail();
        var isMobileValid = validateMobile();
        var isDeptValid = validateDepartment();
        var isPassValid = validatePassword();
        var isConfirmValid = validateConfirmPassword();
        var isCaptchaValid = validateCaptchaInput();

        // Check if all fields passed
        if (isNameValid && isEmailValid && isMobileValid && isDeptValid && isPassValid && isConfirmValid && isCaptchaValid) {
            // Show success message
            var successBox = document.getElementById("successMessage");
            successBox.innerText = "Registration form submitted successfully! All validations passed.";
            successBox.style.display = "block";
            
            // Reset form
            form.reset();
            
            // Clear input styles
            var inputs = form.querySelectorAll("input, select");
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].classList.remove("valid");
            }
            document.getElementById("strengthBar").style.width = "0%";
            document.getElementById("strengthText").innerText = "";

            // Generate new captcha
            generateCaptcha();

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            document.getElementById("successMessage").style.display = "none";
        }
    });
});
