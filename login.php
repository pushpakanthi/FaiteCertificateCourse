<?php
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        if (password_verify($password, $db_password)) {
            $_SESSION['email'] = $email;
            echo 'success';
        } else {
            echo 'Invalid Password';
        }
    } else {
        echo 'User Not Found';
    }
}

$conn->close();
?>


