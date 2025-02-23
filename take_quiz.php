<?php
session_start();

// Ensure session variables are set
if (!isset($_SESSION['user_id'])) {
    die("User ID not set. User may not be logged in.");
}

if (!isset($_SESSION['subject'])) {
    die("Subject not set. Please select a subject.");
}

if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;  // Initialize score if not already set
}

// Processing quiz answers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the answer is correct and increment the score
    if ($_POST['answer'] == $_SESSION['current_question']['correct_option']) {
        $_SESSION['score'] += 1;
    }

    // After all questions are answered, insert score into the database
    if (empty($_SESSION['questions'])) {  // Check if all questions are answered
        // Database connection
        $mysqli = new mysqli("localhost", "root", "", "quiz_app");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare and execute score insertion
        $stmt = $mysqli->prepare("INSERT INTO scores (user_id, subject, score) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Error in prepare: " . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param("isi", $_SESSION['user_id'], $_SESSION['subject'], $_SESSION['score']);
        $execute_result = $stmt->execute();

        if ($execute_result) {
            echo "Score inserted successfully.";
        } else {
            echo "Error inserting score: " . $stmt->error;
        }

        // Ensure no output before the redirect and redirect to the scorecard
        header("Location: scorecard.php");
        exit();
    } else {
        // Continue to the next question if available
        header("Location: take_quiz.php");
        exit();
    }
}
?>
