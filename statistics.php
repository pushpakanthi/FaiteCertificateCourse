<style>
   body {
    background-image: url('satbg.jpeg'); /* Use url() function */
    font-family: Arial, sans-serif;
    background-color: #f4f4f9; /* This will be the background color if the image doesn't load */
    display: flex;
    flex-direction: column;
    align-items: center;
    background-size: cover; /* Makes sure the image covers the entire background */
    background-position: center; /* Centers the background image */
    background-repeat: no-repeat; /* Prevents the image from repeating */
}


    .courses {
        width: 80%;
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        font-size: 24px;
        color: #343a40;
        margin-bottom: 20px;
    }

    #videoPlayerContainer {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    video {
        width: 100%;
        max-width: 700px;
        border: 3px solid #5f3434;
        border-radius: 10px;
    }

    #continueButton {
        background-color: #ffcc00;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    #continueButton:hover {
        background-color: #402121;
    }

    #quizSection {
        margin-top: 20px;
    }

    #quizSection form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    label {
        margin: 10px 0;
        font-size: 16px;
    }

    button[type="submit"] {
        background-color: #5f3434;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    button[type="submit"]:hover {
        background-color: #402121;
    }

    #nextVideoLink {
        margin-top: 20px;
        display: inline-block;
        text-decoration: none;
        color: #5f3434;
        font-weight: bold;
        font-size: 16px;
    }

    #nextVideoLink:hover {
        color: #402121;
    }

    #feedback {
        margin-top: 20px;
        font-size: 16px;
        color: green;
    }
</style>


<?php 
include('connect.php'); // Assuming database connection is correct

// Fetch the current video details
$current_video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 1;

// Fetch the current video from the database
$query = "SELECT * FROM videos WHERE id = $current_video_id LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $video = mysqli_fetch_assoc($result);
    $video_path = $video['video_path'];
} else {
    echo "<p>Video not found in database.</p>";
    exit;
}

// Check for quiz submission
$quiz_data = null; // Initialize quiz data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = $_POST['answer']; // Get user's answer
    $quiz_query = "SELECT * FROM quizzes WHERE video_id = $current_video_id LIMIT 1";
    $quiz_result = mysqli_query($conn, $quiz_query);

    if ($quiz_result && mysqli_num_rows($quiz_result) > 0) {
        $quiz_data = mysqli_fetch_assoc($quiz_result);
        $correct_option = $quiz_data['correct_option'];

        // Check the answer
        if ($user_answer == $correct_option) {
            $feedback = "<p>Your answer is correct!</p>";
        } else {
            $feedback = "<p>Your answer is wrong. The correct answer was option " . $correct_option . ".</p>";
        }

        // Fetch the next video
        $next_video_query = "SELECT * FROM videos WHERE id > $current_video_id LIMIT 1";
        $next_video_result = mysqli_query($conn, $next_video_query);

        if ($next_video_result && mysqli_num_rows($next_video_result) > 0) {
            $next_video = mysqli_fetch_assoc($next_video_result);
            $next_video_id = $next_video['id'];
        } else {
            $next_video_id = null; // No more videos
        }
    } else {
        $feedback = "<p>Quiz not found for this video.</p>";
    }
} else {
    // Fetch the quiz data for the current video only if no POST request
    $quiz_query = "SELECT * FROM quizzes WHERE video_id = $current_video_id LIMIT 1";
    $quiz_result = mysqli_query($conn, $quiz_query);

    if ($quiz_result && mysqli_num_rows($quiz_result) > 0) {
        $quiz_data = mysqli_fetch_assoc($quiz_result);
    }
}
?>

<section class="courses">
    <h2>Course: Statistics - Unit: <?php echo $video['unit_title']; ?></h2>

    <div id="videoPlayerContainer">
        <video id="courseVideo" controls>
            <source src="<?php echo $video_path; ?>" type="video/mp4">
            <p>Your browser does not support the video tag.</p>
        </video>
    </div>

    <button id="continueButton" onclick="playVideo()">Start</button>

    <?php if (isset($feedback)): ?>
        <div id="feedback">
            <?php echo $feedback; ?>
        </div>
    <?php endif; ?>

    <div id="quizSection" style="display: none;">
        <?php if ($quiz_data): ?>
            <form method="POST" action="">
                <p><?php echo $quiz_data['question']; ?></p>
                <label><input type="radio" name="answer" value="1"><?php echo $quiz_data['option1']; ?></label><br>
                <label><input type="radio" name="answer" value="2"><?php echo $quiz_data['option2']; ?></label><br>
                <label><input type="radio" name="answer" value="3"><?php echo $quiz_data['option3']; ?></label><br>
                <label><input type="radio" name="answer" value="4"><?php echo $quiz_data['option4']; ?></label><br>
                <button type="submit">Submit Answer</button>
            </form>
        <?php else: ?>
            <p>Quiz not found for this video.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($next_video_id)): ?>
        <a href="statistics.php?video_id=<?php echo $next_video_id; ?>" style="display: none;" id="nextVideoLink">Next Video</a>
    <?php endif; ?>
</section>

<script>
    let video = document.getElementById('courseVideo');
    let continueButton = document.getElementById('continueButton');
    let quizSection = document.getElementById('quizSection');
    let nextVideoLink = document.getElementById('nextVideoLink');

    function playVideo() {
        video.play();
        continueButton.style.display = 'none'; // Hide the continue button after clicking
    }

    video.onended = function() {
        quizSection.style.display = 'block'; // Show the quiz section after the video ends
    };

    // Show next video link after quiz submission
    <?php if (isset($next_video_id)): ?>
        nextVideoLink.style.display = 'block'; // Show the next video link after quiz
    <?php endif; ?>
</script>
