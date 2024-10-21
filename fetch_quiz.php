<?php
include('connect.php'); // Ensure database connection

$video_id = $_GET['video_id']; // Get video ID from the query string

// Fetch quiz questions from the database
$query = "SELECT * FROM quizzes WHERE video_id = $video_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Query Error: " . mysqli_error($conn);
    exit();
}

while ($quiz = mysqli_fetch_assoc($result)) {
    echo "<div class='quiz-item'>";
    echo "<p>" . $quiz['question'] . "</p>";
    echo "<ul>";
    echo "<li><input type='radio' name='answer_" . $quiz['id'] . "' value='1'>" . $quiz['option1'] . "</li>";
    echo "<li><input type='radio' name='answer_" . $quiz['id'] . "' value='2'>" . $quiz['option2'] . "</li>";
    echo "<li><input type='radio' name='answer_" . $quiz['id'] . "' value='3'>" . $quiz['option3'] . "</li>";
    echo "<li><input type='radio' name='answer_" . $quiz['id'] . "' value='4'>" . $quiz['option4'] . "</li>";
    echo "</ul>";
    echo "</div>";
}

mysqli_close($conn); // Close the database connection
?>
