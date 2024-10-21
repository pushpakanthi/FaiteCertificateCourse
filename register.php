<?php
// Start the session
session_start();

// Include the database connection
include 'connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Check if email already exists
    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($emailCheckQuery);

    if ($result->num_rows > 0) {
        // Email already exists
        echo 'email_exists'; // This will be caught by the AJAX response
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO users (password, first_name, last_name, email) 
                VALUES ('$hashed_password', '$firstName', '$lastName', '$email')";

        if ($conn->query($sql) === TRUE) {
            // Registration successful
            echo 'registration_success'; // This will be caught by the AJAX response
        } else {
            // Handle any error during registration
            echo 'registration_error'; // This will be caught by the AJAX response
        }
    }
}

$conn->close(); // Close the database connection
?>
