<?php
include('connect.php');
date_default_timezone_set('Asia/Colombo');  // Set the timezone

$email = $_POST["email"];
$token = bin2hex(random_bytes(16));  // Generate a random token
$token_hash = hash("sha256", $token);  // Hash the token for security
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);  // Set token expiration time (30 minutes)

// Update the database with the reset token and expiration time
$sql = "UPDATE users
        SET reset_token_hash = ?,
            token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

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
<?php
if ($conn->affected_rows > 0) {
    // Include mailer.php to send the email
    $mail = require __DIR__ . "/mailer.php"; // Ensure mailer.php is included correctly
    
    // Set email parameters
    $mail->setFrom("faiteplus08@gmail.com", "Faite Website Support");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset Request";
    
    // Create the email body (HTML format)
    $mail->Body = <<<END
    <p>Click <a href="http://localhost/Faite_learn_website/reset_password.php?token=$token">here</a> to reset your password.</p>
    <p>This link will expire in 30 minutes.</p>
    END;
    
    // Try to send the email and catch errors
    try {
        $mail->send();
        // SweetAlert for successful email sent
        echo "
        Swal.fire({
            title: 'Success!',
            text: 'Message sent, please check your inbox.',
            icon: 'success',
            confirmButtonText: 'Okay'
        }).then(function() {
            window.location.href = 'login.html'; // Redirect to login after the alert
        });
        ";
    } catch (Exception $e) {
        echo "
        Swal.fire({
            title: 'Error!',
            text: 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}',
            icon: 'error',
            confirmButtonText: 'Try Again'
        }).then(function() {
            window.location.href = 'login.html'; // Redirect to login after the alert
        });
        ";
    }
} else {
    // SweetAlert for no user found
    echo "
    Swal.fire({
        title: 'Oops!',
        text: 'No user found with that email address.',
        icon: 'error',
        confirmButtonText: 'Try Again'
    }).then(function() {
            window.location.href = 'login.html'; // Redirect to login after the alert
        });
    ";
}
?>

</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
