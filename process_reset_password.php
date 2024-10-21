<?php
session_start();

include('connect.php');

$token = $_POST['token'];

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

// Hash the new password using password_hash()
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE users 
    SET password = ?,
    reset_token_hash = null,
    token_expires_at = null
    WHERE u_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $password_hash, $user["u_id"]);
$stmt->execute();

// After the password is successfully updated, display the SweetAlert and prevent automatic redirection.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        title: 'Success!',
        text: 'Your password has been successfully updated! 🎉 You can now log in and continue your journey with us. 😊',
        icon: 'success',
        confirmButtonText: 'Okay',
        timer: 5000
    }).then(function() {
        window.location.href = 'login.html'; // Redirect to login page after closing the alert
    });
</script>

</body>
</html>
