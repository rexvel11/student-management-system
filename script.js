// DOM Elements
const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const loginForm = document.getElementById('login');
const registerForm = document.getElementById('register');
const passwordInput = document.getElementById('password');
const passwordWarning = document.getElementById('password-warning');

// Utility: Clear all input fields in a form
function clearFormInputs(form) {
    form.querySelectorAll('input').forEach(input => input.value = '');
}

// Utility: Switch between forms
function toggleForms(hideForm, showForm) {
    hideForm.style.display = "none";
    showForm.style.display = "block";
}

// Event: Handle password input validation
function validatePassword() {
    if (passwordInput.value.length < 8) {
        passwordWarning.style.display = 'block';
    } else {
        passwordWarning.style.display = 'none';
    }
}

// Display server-side errors if they exist
if (typeof serverErrors !== 'undefined' && serverErrors.length > 0) {
    alert(serverErrors.join('\n')); // Show errors in an alert box
    toggleForms(loginForm, registerForm); // Stay on the registration form
    if (typeof formData !== 'undefined') {
        populateFormData(formData); // Restore form values
    }
}

// Event: Switch to the Register form
function handleSignUpClick(event) {
    event.preventDefault(); // Prevent default button behavior
    clearFormInputs(loginForm);
    toggleForms(loginForm, registerForm);
}

// Event: Switch to the Login form
function handleSignInClick(event) {
    event.preventDefault(); // Prevent default button behavior
    clearFormInputs(registerForm);
    toggleForms(registerForm, loginForm);
}

// Initialize Event Listeners
function initializeEventListeners() {
    // Validate password dynamically
    passwordInput.addEventListener('input', validatePassword);

    // Switch forms on button clicks
    signUpButton.addEventListener('click', handleSignUpClick);
    signInButton.addEventListener('click', handleSignInClick);
}

// Initialization
function initialize() {
    initializeEventListeners();
}

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const warning = document.getElementById("password-warning");

    passwordInput.addEventListener("input", () => {
        if (passwordInput.value.length < 8) {
            warning.style.display = "block";
        } else {
            warning.style.display = "none";
        }
    });
});

// Initialize the script once the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', initialize);
