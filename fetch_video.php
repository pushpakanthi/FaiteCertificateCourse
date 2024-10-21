<?php
include('connect.php'); // Assuming database connection is correct

$course_name = 'Statistics'; // Example course name
$unit_title = 'Introduction of Statistics'; // Example unit title

// Fetch video from database based on course_name and unit_title
$query = "SELECT * FROM videos WHERE course_name = '$course_name' AND unit_title = '$unit_title' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    // Query failed, print error
    echo "Query Error: " . mysqli_error($conn);
} else {
    if (mysqli_num_rows($result) > 0) {
        $video = mysqli_fetch_assoc($result);
        echo "Video Data: ";
        print_r($video);  // Debugging: Print the video data to check
        $video_path = $video['video_path']; // Ensure 'video_path' column exists and is correct
    } else {
        echo "No video found for course_name = '$course_name' and unit_title = '$unit_title'";
    }
}
?>
