<?php
session_start();
include('connect.php');

$token = $_GET['token'];

// Hash the token from the URL before comparing
$token_hash = hash("sha256", $token);

// Find the user with the matching hashed token and check if it's not expired
$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
if ($user == null) {
    die("Token not found");
}

if (strtotime($user["token_expires_at"]) <= time()) {
    die("Token has expired");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome for the eye icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        /* Style for the eye icon inside the input field */
        .input-group {
            position: relative;
        }

        .input-group input[type="password"] {
            width: 100%;
            padding-right: 30px; /* space for the eye icon */
        }

        .input-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-box">
            <h2>Reset Password</h2>
            <form id="resetPasswordForm" name="resetPasswordForm" method="post" action="process_reset_password.php" onsubmit="return validateForm();">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="input-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="New Password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                </div>
                
                <button type="submit" class="submit-button">Reset Password</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle visibility of password field
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        
        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Toggle visibility of confirm password field
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordField = document.getElementById('confirm_password');
        
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Validation function
        function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/; // Regex for password strength

            // Check if passwords match
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Passwords do not match!',
                    footer: 'Please make sure both passwords are the same.'
                });
                return false;
            }

            // Check password strength
            if (!passwordRegex.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Weak Password!',
                    text: 'Password must be at least 8 characters long, and include a lowercase letter, an uppercase letter, and a special character.',
                    footer: 'Try using a stronger password.'
                });
                return false;
            }

            return true; // Submit the form if validation passes
        }
    </script>
</body>
</html>

